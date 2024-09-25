<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class CashController extends AppBaseController
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function pay(Request $request): JsonResponse
    {
        $input = $request->all();

        $data = $this->subscriptionRepository->manageCashSubscription($input['plan_id']);

        if (! isset($data['plan'])) {
            if (isset($data['status']) && $data['status'] == true) {
                return $this->sendSuccess($data['subscriptionPlan']->name.' '.__('messages.subscription_pricing_plans.has_been_subscribed'));
            } else {
                if (isset($data['status']) && $data['status'] == false) {
                    return $this->sendError(__('messages.flash.not_switch_to_zero_plan'));
                }
            }
        }

        // If call returns body in response, you can get the deserialized version from the result attribute of the response
        $subscriptionId = $data['subscription']->id;
        $subscriptionAmount = $data['amountToPay'];

        $transaction = Transaction::create([
            'transaction_id' => '',
            'payment_mode' => Transaction::TYPE_CASH,
            'amount' => $subscriptionAmount,
            'user_id' => getLogInUserId(),
            'tenant_id' => Auth::user()->tenant_id,
            'status' => Subscription::INACTIVE,
            'meta' => '',
        ]);

        if (isset($input['payment_attachments']) && ! empty($input['payment_attachments'])) {
            foreach ($input['payment_attachments'] as $paymentAttachment) {
                $transaction->addMedia($paymentAttachment)->toMediaCollection(Transaction::PAYMENT_ATTACHMENTS,
                    config('app.media_disc'));
            }
        }

        // updating the transaction id on the subscription table
        $subscription = Subscription::with('subscriptionPlan')->findOrFail($subscriptionId);
        $subscription->update(['transaction_id' => $transaction->id]);

        Flash::success(trans(__('messages.flash.payment_done_admin_approve'), [], getLoggedInUser()->language));

        return response()->json(['url' => route('subscription.pricing.plans.index')]);
    }
}
