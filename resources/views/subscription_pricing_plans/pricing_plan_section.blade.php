<div class="col-xl-4 my-4">
    <div class="card pricing-card text-center">
        <div class="card-body px-10 py-14">
            <div class="mb-7 text-center">
                <h3 class="text-gray-900 fs-2">{{ $subscriptionsPricingPlan->name }}</h3>
                <div class="justify-content-center mt-5">
                    <h4 class="text-center mb-6 mt-2">
                        {{ getAdminSubscriptionPlanCurrencyIcon($subscriptionsPricingPlan->currency_id) }}
                        <span class="fa-3x fw-bolder">{{ number_format($subscriptionsPricingPlan->price) }}</span>
                        <span class="h6 text-gray-800 ml-2">/ {{ $subscriptionsPricingPlan->frequency === 1 ? __('messages.admin_dashboard.month') : __('messages.admin_dashboard.year') }}</span>
                        <br>
                    </h4>
                    <span class="fw-bold opacity-50">{{ __('messages.subscription_plans.client_limit') }}
                        :</span> <span class="fs-7 fw-bold">{{ $subscriptionsPricingPlan->client_limit}}</span><br>
                    <span class="fw-bold opacity-50">{{ __('messages.subscription_plans.invoice_limit') }}
                        :</span> <span class="fs-7 fw-bold">{{ $subscriptionsPricingPlan->invoice_limit}}</span>
                </div>
                <div class="pricing-card__data">
                    <ul class="pl-0 list-style-none">
                        @if (isAuth() && count($subscriptionsPricingPlan->subscription) > 0)
                            @php
                                $activeSubscription = currentActiveSubscription();
                            @endphp
                            @if($activeSubscription && $activeSubscription->trial_ends_at != null && $activeSubscription->subscription_plan_id == $subscriptionsPricingPlan->id)
                                <li>
                                    <h4 class="text-center mt-5">{{ __('messages.subscription_plans.valid_until') }}
                                        : {{ $subscriptionsPricingPlan->trial_days }}
                                    </h4>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('admin'))
                                @if($activeSubscription && isAuth() &&  $activeSubscription->subscriptionPlan->id == $subscriptionsPricingPlan->id)
                                    <li>
                                        <h4 class="text-center mt-5">
                                            {{ __('messages.subscription_plans.end_date') }}
                                            :
                                            {{ getParseDate($activeSubscription->end_date)->format('d-m-Y') }}
                                        </h4>
                                    </li>
                                @endif
                            @endif
                        @endif
                    </ul>
                </div>

            </div>

            @php
                $currentActiveSubscription = currentActiveSubscription();
            @endphp

            @if($currentActiveSubscription && $subscriptionsPricingPlan->id == $currentActiveSubscription->subscription_plan_id && !$currentActiveSubscription->isExpired())
                @if($subscriptionsPricingPlan->price != 0)
                    <button type="button"
                            class="btn btn-success text-capitalize rounded-pill mx-auto d-block cursor-remove-plan pricing-plan-button-active"
                            data-id="{{ $subscriptionsPricingPlan->id }}">
                        {{ __('messages.subscription_plans.currently_active') }}</button>
                @else
                    <button type="button" class="btn btn-info rounded-pill mx-auto d-block cursor-remove-plan">
                        {{ __('messages.flash.free_plan_cannot_be_renewed_chosen_again') }}
                    </button>
                @endif
            @else
                @if($currentActiveSubscription && !$currentActiveSubscription->isExpired() && ($subscriptionsPricingPlan->price == 0 || $subscriptionsPricingPlan->price != 0))
                    @if($subscriptionsPricingPlan->hasZeroPlan->count() == 0)
                        <a data-turbo="false"
                           href="{{ $subscriptionsPricingPlan->price != 0 ? route('choose.payment.type', $subscriptionsPricingPlan->id) : 'javascript:void(0)' }}"
                           class="btn btn-primary w-50 text-capitalize rounded-pill mx-auto d-block {{ $subscriptionsPricingPlan->price == 0 ? 'freePayment' : ''}}"
                           data-id="{{ $subscriptionsPricingPlan->id }}"
                           data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                            {{ __('messages.subscription_plans.switch_plan') }}</a>
                    @else
                        <button type="button" class="btn btn-info rounded-pill mx-auto d-block cursor-remove-plan">
                            {{ __('messages.flash.free_plan_cannot_be_renewed_chosen_again') }}
                        </button>
                    @endif
                @else
                    @if($subscriptionsPricingPlan->hasZeroPlan->count() == 0)
                        <a href="{{ $subscriptionsPricingPlan->price != 0 ? route('choose.payment.type', $subscriptionsPricingPlan->id) : 'javascript:void(0)' }}"
                           class="btn btn-primary text-capitalize rounded-pill mx-auto d-block {{ $subscriptionsPricingPlan->price == 0 ? 'freePayment' : ''}}"
                           data-id="{{ $subscriptionsPricingPlan->id }}"
                           data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                            {{ __('choose plan') }}</a>
                    @else
                        <button type="button" class="btn btn-info rounded-pill mx-auto d-block cursor-remove-plan">
                            {{ __('messages.flash.free_plan_cannot_be_renewed_chosen_again') }}
                        </button>
                    @endif
                @endif
            @endif

        </div>
    </div>
</div>
