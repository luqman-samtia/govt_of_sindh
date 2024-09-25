<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class RazorpayRepository
 */
class RazorpayRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'stripe_id',
        'stripe_status',
        'stripe_plan',
        'subscription_plan_id',
        'transaction_id',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return Subscription::class;
    }

    public function manageSubscription(int $subscriptionPlanId): array
    {
        /** @var SubscriptionPlan $subscriptionPlan */
        $subscriptionPlan = SubscriptionPlan::findOrFail($subscriptionPlanId);
        $newPlanDays = $subscriptionPlan->frequency == SubscriptionPlan::MONTH ? 30 : 365;

        $startsAt = Carbon::now();
        $endsAt = $startsAt->copy()->addDays($newPlanDays);

        $usedTrialBefore = Subscription::whereUserId(Auth::id())->whereNotNull('trial_ends_at')->exists();

        // if the user did not have any trial plan then give them a trial
        if (! $usedTrialBefore && $subscriptionPlan->trial_days > 0) {
            $endsAt = $startsAt->copy()->addDays($subscriptionPlan->trial_days);
        }

        $amountToPay = $subscriptionPlan->price;

        /** @var Subscription $currentSubscription */
        $currentSubscription = currentActiveSubscription();

        $usedDays = Carbon::parse($currentSubscription->start_date)->diffInDays($startsAt);
        $planIsInTrial = checkIfPlanIsInTrial($currentSubscription);
        // switching the plan -- Manage the pro-rating
        if (! $currentSubscription->isExpired() && $amountToPay != 0 && ! $planIsInTrial) {
            $usedDays = Carbon::parse($currentSubscription->start_date)->diffInDays($startsAt);

            $currentPlan = $currentSubscription->subscriptionPlan; // TODO: take fields from subscription

            // checking if the current active subscription plan has the same price and frequency in order to process the calculation for the proration
            $planPrice = $currentPlan->price;
            $planFrequency = $currentPlan->frequency;
            if ($planPrice != $currentSubscription->plan_amount || $planFrequency != $currentSubscription->plan_frequency) {
                $planPrice = $currentSubscription->plan_amount;
                $planFrequency = $currentSubscription->plan_frequency;
            }

            $frequencyDays = $planFrequency == SubscriptionPlan::MONTH ? 30 : 365;
            $perDayPrice = round($planPrice / $frequencyDays, 2);

            $remainingBalance = $planPrice - ($perDayPrice * $usedDays);

            if ($remainingBalance < $subscriptionPlan->price) { // adjust the amount in plan i.e. you have to pay for it
                $amountToPay = round($subscriptionPlan->price - $remainingBalance, 2);
            } else {
                $perDayPriceOfNewPlan = round($subscriptionPlan->price / $newPlanDays, 2);

                $totalDays = round($remainingBalance / $perDayPriceOfNewPlan);
                $endsAt = Carbon::now()->addDays($totalDays);
                $amountToPay = 0;
            }
        }

        // check that if try to switch the plan
        if (! $currentSubscription->isExpired()) {
            if ((checkIfPlanIsInTrial($currentSubscription) || ! checkIfPlanIsInTrial($currentSubscription)) && $subscriptionPlan->price <= 0) {
                return ['status' => false, 'subscriptionPlan' => $subscriptionPlan];
            }
        }

        if ($usedDays <= 0) {
            $startsAt = $currentSubscription->start_date;
        }

        $input = [
            'user_id' => getLoggedInUser()->id,
            'subscription_plan_id' => $subscriptionPlan->id,
            'plan_amount' => $subscriptionPlan->price,
            'plan_frequency' => $subscriptionPlan->frequency,
            'start_date' => $startsAt,
            'end_date' => $endsAt,
            'status' => Subscription::INACTIVE,
        ];

        $subscription = Subscription::create($input);

        if ($subscriptionPlan->price <= 0 || $amountToPay == 0) {
            // De-Active all other subscription
            Subscription::whereUserId(getLogInUserId())
                ->where('id', '!=', $subscription->id)
                ->update([
                    'status' => Subscription::INACTIVE,
                ]);
            Subscription::findOrFail($subscription->id)->update(['status' => Subscription::ACTIVE]);

            return ['status' => true, 'subscriptionPlan' => $subscriptionPlan];
        }

        session(['subscription_plan_id' => $subscription->id]);
        session(['from_pricing' => request()->get('from_pricing')]);

        return [
            'plan' => $subscriptionPlan,
            'amountToPay' => $amountToPay,
            'subscription' => $subscription,
        ];
    }

    public function paymentSuccess($input)
    {
        try {
            DB::beginTransaction();
            Log::info('RazorPay Payment Successfully');
            $api = new Api(getSuperAdminRazorpayKey(), getSuperAdminRazorpaySecret());

            if (count($input) && ! empty($input['razorpay_payment_id'])) {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $generatedSignature = hash_hmac('sha256', $payment['order_id'].'|'.$input['razorpay_payment_id'],
                    getSuperAdminRazorpaySecret());
                if ($generatedSignature != $input['razorpay_signature']) {
                    return redirect()->back();
                }

                $subscriptionPlanId = $payment['notes']['subscription_id'];
                $subscription = Subscription::findOrFail($subscriptionPlanId)->update(['status' => Subscription::ACTIVE]);
                $subscriptionPlan = Subscription::where('id', $subscriptionPlanId)->first();
                $subscriptionPlanIds = $subscriptionPlan->subscription_plan_id;
                $subscriptionPlanData = SubscriptionPlan::whereId($subscriptionPlanIds)->first();
                $subscriptionPlanCurrency = getAdminSubscriptionPlanCurrencyIcon($subscriptionPlanData->currency_id);
                $amount = $payment['amount'];
                $transactionID = $payment['id'];     // $response->result->id gives the orderId of the order created above

                // De-Active all other subscription
                Subscription::whereUserId(getLogInUserId())
                    ->where('id', '!=', $subscriptionPlanId)
                    ->update([
                        'status' => Subscription::INACTIVE,
                    ]);

                $user = Auth::user();
                $transaction = Transaction::create([
                    'transaction_id' => $transactionID,
                    'payment_mode' => Transaction::TYPE_RAZORPAY,
                    'amount' => $amount / 100,
                    'user_id' => $user->id,
                    'tenant_id' => $user->tenant_id,
                    'status' => Transaction::PAID,
                    'meta' => json_encode($payment),
                ]);

                $totalAmount = $amount / 100;
                $title = 'You successfully received subscription plan amount '.$subscriptionPlanCurrency.$totalAmount.' from '.Auth::user()->full_name.'.';
                addNotification([
                    Notification::NOTIFICATION_TYPE['Subscription Plan Purchased'],
                    getSuperAdmin()->id,
                    $title,
                ]);
                // updating the transaction id on the subscription table
                $subscription = Subscription::with('subscriptionPlan')->findOrFail($subscriptionPlanId);
                $subscription->update(['transaction_id' => $transaction->id]);
                DB::commit();

                return $subscription;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('RazorPay Payment Failed Error:'.$e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function paymentFailed($subscription)
    {
        $subscriptionPlan = Subscription::findOrFail($subscription);
        $subscriptionPlan->delete();

        return $subscriptionPlan;
    }
}
