<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Repositories\RazorpayRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Razorpay\Api\Api;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AdminRazorpayController extends AppBaseController
{
    /**
     * @var RazorpayRepository
     */
    private $razorPayRepo;

    public function __construct(RazorpayRepository $razorpayRepository)
    {
        $this->razorPayRepo = $razorpayRepository;
    }

    public function onBoard(Request $request)
    {
        $input = $request->all();

        try {
            $subscriptionsPricingPlan = SubscriptionPlan::findOrFail($input['plan_id']);
            if ($subscriptionsPricingPlan->currency_id != null && ! in_array(getAdminSubscriptionPlanCurrencyCode(strtoupper($subscriptionsPricingPlan->currency_id)),
                getRazorPaySupportedCurrencies())) {
                Flash::error(__('messages.flash.currency_not_supported_razorpay'));

                return response()->json(['url' => route('subscription.pricing.plans.index')]);
            }

            $subscriptionData = $this->razorPayRepo->manageSubscription($input['plan_id']);

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
            $user = Auth::user();
            $api = new Api(getSuperAdminRazorpayKey(), getSuperAdminRazorpaySecret());
            $orderData = [
                'receipt' => $subscriptionData['plan']->id,
                'amount' => $subscriptionData['amountToPay'] * 100, // 100 = 1 rupees
                'currency' => getAdminSubscriptionPlanCurrencyCode(strtoupper($subscriptionData['plan']->currency_id)),
                'notes' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'subscription_id' => $subscriptionData['subscription']->id,
                ],
            ];

            $razorpayOrder = $api->order->create($orderData);
            $data['id'] = $razorpayOrder->id;
            $data['amount'] = $subscriptionData['amountToPay'] * 100;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['planID'] = $subscriptionData['subscription']->id;

            return $this->sendResponse($data, __('messages.flash.order_created'));
        } catch (HttpException $ex) {
            throw new UnprocessableEntityHttpException($ex->getMessage());
        }
    }

    public function paymentSuccess(Request $request): Redirector|RedirectResponse|Application
    {
        $input = $request->all();

        try {
            $subscription = $this->razorPayRepo->paymentSuccess($input);

            Flash::success($subscription->subscriptionPlan->name.' '.__('messages.flash.has_subscribed'));

            return redirect(route('subscription.pricing.plans.index'));
        } catch (Exception $e) {
            Log::info('RazorPay Payment Failed Error:'.$e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function paymentFailed(Request $request): JsonResponse|Redirector|Application|RedirectResponse
    {
        try {
            $data = $request->get('data');
            Log::info('payment failed');
            Log::info($data);
            $subscription = session('subscription_plan_id');

            /** @var RazorpayRepository $RazorpayRepo */
            $this->razorPayRepo->paymentFailed($subscription);

            Flash::error(__('messages.flash.unable_to_process_the_payment'));

            return redirect(route('subscription.pricing.plans.index'));
        } catch (\Exception $ex) {
            throw new UnprocessableEntityHttpException($ex->getMessage());
        }
    }

    public function paymentFailedModal(): JsonResponse|Redirector|Application|RedirectResponse
    {
        try {
            $subscription = session('subscription_plan_id');

            /** @var RazorpayRepository $RazorpayRepo */
            $this->razorPayRepo->paymentFailed($subscription);

            Flash::error(__('messages.flash.unable_to_process_the_payment'));
        
            return redirect(route('subscription.pricing.plans.index'));
        } catch (HttpException $ex) {
            throw new UnprocessableEntityHttpException($ex->getMessage());
        }
    }
}
