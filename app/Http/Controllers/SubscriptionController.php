<?php

namespace App\Http\Controllers;

use App\Repositories\SubscriptionRepository;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Stripe\Exception\ApiErrorException;

/**
 * Class SubscriptionController
 */
class SubscriptionController extends AppBaseController
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepo;

    public function __construct(SubscriptionRepository $subscriptionRepo)
    {
        $this->subscriptionRepo = $subscriptionRepo;
    }

    /**
     *
     * @throws ApiErrorException
     */
    public function purchaseSubscription(Request $request)
    {
        $subscriptionPlanId = $request->get('plan_id');
        $result = $this->subscriptionRepo->purchaseSubscriptionForStripe($subscriptionPlanId);

        // returning from here if the plan is free.
        if (isset($result['status']) && $result['status'] == true) {
            return $this->sendSuccess($result['subscriptionPlan']->name.' '.__(__('messages.flash.has_subscribed')));
        }

        if (isset($result['status']) && $result['status'] == false) {
            return $this->sendError(__('messages.flash.not_switch_to_zero_plan'));
        }

        return $this->sendResponse($result, __('messages.flash.session_created'));
    }

    /**
     *
     * @throws ApiErrorException
     */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        /** @var SubscriptionRepository $subscriptionRepo */
        $subscriptionRepo = app(SubscriptionRepository::class);
        $subscription = $subscriptionRepo->paymentUpdate($request);
        Flash::success($subscription->subscriptionPlan->name.' '.__(__('messages.flash.has_subscribed')));

        return redirect(route('subscription.pricing.plans.index'));
    }

    public function handleFailedPayment(): RedirectResponse
    {
        $subscriptionPlanId = session('subscription_plan_id');
        /** @var SubscriptionRepository $subscriptionRepo */
        $subscriptionRepo = app(SubscriptionRepository::class);
        $subscriptionRepo->paymentFailed($subscriptionPlanId);
        Flash::error(__('messages.flash.unable_to_process_the_payment'));

        return redirect(route('subscription.pricing.plans.index'));
    }
}
