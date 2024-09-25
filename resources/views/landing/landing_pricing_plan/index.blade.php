<!-- app-rate-section -->
<section class="app-rate">
    <div class="container">
        <div class="text-center">
            <h4 class="fs-6 text-primary fw-bold mb-3">{{ strtoupper(__('messages.landing.our_app_rates')) }}</h4>
            <h2 class="fw-bold max-w-680 mx-auto text-white">{{ __('messages.landing.pricing_plans') }}</h2>
        </div>
        <div class="pricing-plan-section">
            <div class="owl-carousel owl-theme">
                @foreach($subscriptionPricingPlans as $subscriptionsPricingPlan)
                    <div class="text-center">
                        <div class="pricing-plan item">
                            <div class="plan-top">
                                <h4 class="fs-6 text-success fw-bold mb-3">{{ $subscriptionsPricingPlan->name }}</h4>
                                <h3 class="text-primary mb-0">
                                    {{ getAdminSubscriptionPlanCurrencyIcon
($subscriptionsPricingPlan->currency_id).number_format($subscriptionsPricingPlan->price) }}
                                    <span class="fs-small">/
                                        {{ \App\Models\SubscriptionPlan::PLAN_TYPE_SORT_NAME[$subscriptionsPricingPlan->frequency] }}
                                    </span>
                                </h3>
                            </div>
                            <div class="plan-bottom"><p class="text-gray-200 fs-16">{{ __('messages.subscription_plans.client_limit') }}: <span
                                        class="text-success fw-bold">{{ $subscriptionsPricingPlan->client_limit }}</span></p>
                                <p class="text-gray-200 fs-16">{{ __('messages.subscription_plans.invoice_limit') }}: <span
                                        class="text-success fw-bold">{{ $subscriptionsPricingPlan->invoice_limit }}</span></p>
                                <div class="started-btn mt-4 pt-2"><a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.landing.get_started') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
