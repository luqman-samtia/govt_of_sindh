<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Repositories\SubscriptionRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class AdminPaypalController extends Controller
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * PaypalController constructor.
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @throws Throwable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onBoard(Request $request): JsonResponse
    {
        $subscriptionsPricingPlan = SubscriptionPlan::find($request->get('planId'));

        if ($subscriptionsPricingPlan->currency_id != null && ! in_array(getAdminSubscriptionPlanCurrencyCode(strtoupper($subscriptionsPricingPlan->currency_id)),
            getPayPalSupportedCurrencies())) {
            Flash::error(__('messages.flash.currency_not_supported_paypal'));

            return response()->json(['url' => route('subscription.pricing.plans.index')]);
        }

        $data = $this->subscriptionRepository->manageSubscription($request->get('planId'));

        if (! isset($data['plan'])) { // 0 amount plan or try to switch the plan if it is in trial mode
            // returning from here if the plan is free.
            if (isset($data['status']) && $data['status'] == true) {
                return $this->sendSuccess($data['subscriptionPlan']->name.' '.__('messages.subscription_pricing_plans.has_been_subscribed'));
            } else {
                if (isset($data['status']) && $data['status'] == false) {
                    return $this->sendError(__('messages.flash.not_switch_to_zero_plan'));
                }
            }
        }

        $subscriptionsPricingPlan = $data['plan'];
        $subscription = $data['subscription'];

        config([
            'paypal.mode' => config('payments.paypal.mode'),
            'paypal.sandbox.client_id' => getSuperAdminPaypalClientId(),
            'paypal.sandbox.client_secret' => getSuperAdminPaypalSecret(),
            'paypal.live.client_id' => getSuperAdminPaypalClientId(),
            'paypal.live.client_secret' => getSuperAdminPaypalSecret(),
        ]);

        $provider = new PayPalClient();
        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $subscription->id,
                    'amount' => [
                        'value' => $data['amountToPay'],
                        'currency_code' => getAdminSubscriptionPlanCurrencyCode($subscriptionsPricingPlan->currency_id),
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('admin.paypal.failed'),
                'return_url' => route('admin.paypal.success'),
            ],
        ];

        $order = $provider->createOrder($data);
        session(['payment_type' => request()->get('payment_type')]);

        return response()->json($order);
    }

    public function failed(): Redirector|Application|RedirectResponse
    {
        $subscription = session('subscription_plan_id');
        $subscriptionPlan = Subscription::find($subscription);
        $subscriptionPlan->delete();

        Flash::error(__('messages.flash.unable_to_process_the_payment'));

        return redirect(route('subscription.pricing.plans.index'));
    }

    /**
     * @throws Throwable
     */
    public function success(Request $request): RedirectResponse
    {
        config([
            'paypal.mode' => config('payments.paypal.mode'),
            'paypal.sandbox.client_id' => getSuperAdminPaypalClientId(),
            'paypal.sandbox.client_secret' => getSuperAdminPaypalSecret(),
            'paypal.live.client_id' => getSuperAdminPaypalClientId(),
            'paypal.live.client_secret' => getSuperAdminPaypalSecret(),
        ]);

        $provider = new PayPalClient();
        $provider->getAccessToken();
        $token = $request->get('token');
        $orderInfo = $provider->showOrderDetails($token);

        try {
            $response = $provider->capturePaymentOrder($token);
            $subscriptionId = $response['purchase_units'][0]['reference_id'];
            $subscriptionAmount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $transactionID = $response['id'];

            $subscriptionPlan = Subscription::with('subscriptionPlan')->where('id', $subscriptionId)->firstOrFail();
            $subscriptionPlan->update(['status' => Subscription::ACTIVE]);
            $subscriptionPlanData = $subscriptionPlan->subscriptionPlan;

            $subscriptionPlanCurrency = getAdminSubscriptionPlanCurrencyIcon($subscriptionPlanData->currency_id);
            // De-Active all other subscription
            Subscription::whereUserId(getLogInUserId())
                ->where('id', '!=', $subscriptionId)
                ->update([
                    'status' => Subscription::INACTIVE,
                ]);

            $transaction = Transaction::create([
                'transaction_id' => $transactionID,
                'payment_mode' => Transaction::TYPE_PAYPAL,
                'amount' => $subscriptionAmount,
                'user_id' => getLogInUserId(),
                'tenant_id' => Auth::user()->tenant_id,
                'status' => Transaction::PAID,
                'meta' => json_encode($response),
            ]);

            $title = 'You successfully received subscription plan amount '.$subscriptionPlanCurrency.$subscriptionAmount.' from '.Auth::user()->full_name.'.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Subscription Plan Purchased'],
                getSuperAdmin()->id,
                $title,
            ]);

            // updating the transaction id on the subscription table
            $subscription = Subscription::with('subscriptionPlan')->findOrFail($subscriptionId);
            $subscription->update(['transaction_id' => $transaction->id]);

            Flash::success($subscription->subscriptionPlan->name.' '.__('messages.flash.has_subscribed'));

            return redirect(route('subscription.pricing.plans.index'));
        } catch (HttpException $ex) {
            print_r($ex->getMessage());
        }
    }
}
