@extends('layouts.app')
@section('title')
    {{ __('messages.subscription_plans.payment_type') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            @php
                $cpData = getCurrentPlanDetails();
                $planText = $cpData['isExpired'] ? 'Current Expired Plan' : __('messages.subscription_plans.current_plan');
                $currentPlan = $cpData['currentPlan'];
            @endphp

            <div class="card-body p-lg-10">
                <div class="row">
                    @if (currentActiveSubscription()->end_date >= \Carbon\Carbon::now())
                        <div class="col-md-6 col-12 mb-md-0 mb-10">
                            <div class="card plan-card-shadow h-100 card-xxl-stretch p-5 me-md-2">
                                <div class="card-header border-0 px-0">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span
                                            class="card-label fw-bolder text-primary fs-1 mb-1 me-0">{{ $planText }}</span>
                                    </h3>
                                </div>
                                <div class="card-body py-3 px-0">
                                    <div class="flex-stack">
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.plan_name') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">{{ $cpData['name'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-3 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.plan_price') }}</h4>
                                            <span class="fs-5 text-muted fw-bold mt-1">
                                                <span class="mb-2">
                                                    {{ getAdminSubscriptionPlanCurrencyIcon($currentPlan->currency_id) }}
                                                </span>
                                                {{ number_format($currentPlan->price) }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.start_date') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">{{ $cpData['startAt'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.end_date') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">{{ $cpData['endsAt'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.used_days') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">{{ $cpData['usedDays'] }}
                                                Days</span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.remaining_days') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">{{ $cpData['remainingDays'] }}
                                                Days</span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.used_balance') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">
                                                <span class="mb-2">
                                                    {{ getAdminSubscriptionPlanCurrencyIcon($currentPlan->currency_id) }}
                                                </span>
                                                {{ $cpData['usedBalance'] }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center plan-border-bottom py-2">
                                            <h4 class="fs-5 w-50 mb-0 me-5 text-gray-800 fw-bolder">
                                                {{ __('messages.subscription_plans.remaining_balance') }}</h4>
                                            <span class="fs-5 w-50 text-muted fw-bold mt-1">
                                                <span
                                                    class="mb-2">{{ getAdminSubscriptionPlanCurrencyIcon($currentPlan->currency_id) }}</span>
                                                {{ $cpData['remainingBalance'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php
                        $newPlan = getProratedPlanData($subscriptionsPricingPlan->id);
                    @endphp
                    <div class="col-md-6 col-12">
                        <div class="card plan-card-shadow h-100 card-xxl-stretch p-5 ms-md-2">
                            <div class="card-header border-0 px-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span
                                        class="card-label fw-bolder text-primary fs-1 mb-1 me-0">{{ __('messages.subscription_plans.new_plan') }}</span>
                                </h3>
                            </div>
                            <div class="card-body py-3 px-0">
                                <div class="flex-stack">
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.plan_name') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">{{ $newPlan['name'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.plan_price') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">
                                            <span class="mb-2">
                                                {{ getAdminSubscriptionPlanCurrencyIcon($subscriptionsPricingPlan->currency_id) }}
                                            </span>
                                            {{ number_format($subscriptionsPricingPlan->price) }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.start_date') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">{{ $newPlan['startDate'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.end_date') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">{{ $newPlan['endDate'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.total_days') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">{{ $newPlan['totalDays'] }} Days</span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.Remaining_Balance_of_Prev_Plan') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">
                                            {{ getAdminSubscriptionPlanCurrencyIcon($subscriptionsPricingPlan->currency_id) }}
                                            {{ $newPlan['remainingBalance'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center plan-border-bottom py-2">
                                        <h4 class="fs-5 w-50 plan-data mb-0 me-5 text-gray-800 fw-bolder">
                                            {{ __('messages.subscription_plans.amount_to_pay') }}</h4>
                                        <span class="fs-5 text-muted fw-bold mt-1">
                                            {{ getAdminSubscriptionPlanCurrencyIcon($subscriptionsPricingPlan->currency_id) }}
                                            {{ $newPlan['amountToPay'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($transaction)
                    <div class="row justify-content-center">
                        <div
                            class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 d-flex justify-content-center align-items-center mt-5 plan-controls">
                            <div
                                class="mt-5 me-3 border border-gray-400 w-50 {{ $newPlan['amountToPay'] <= 0 ? 'd-none' : '' }}">
                                {{ Form::select('payment_type', $paymentTypes, \App\Models\Subscription::TYPE_STRIPE, ['class' => 'form-select form-select-solid', 'required', 'id' => 'paymentType', 'data-control' => 'select2']) }}
                            </div>
                            <div class="mt-5 stripePayment proceed-to-payment">
                                <button type="button" class="btn btn-primary rounded-pill mx-auto d-block makePayment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 paypalPayment proceed-to-payment d-none">
                                <button type="button" class="btn btn-primary rounded-pill mx-auto d-block paymentByPaypal"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 razorPayPayment proceed-to-razor-pay-payment d-none">
                                <button type="button"
                                    class="btn btn-primary rounded-pill mx-auto d-block razor_pay_payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 paystackPayPayment d-none">
                                <button type="button"
                                    class="btn btn-primary rounded-pill mx-auto d-block paystack-pay-payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 cashPayment proceed-cash-payment d-none">
                                <button type="button" class="btn btn-primary rounded-pill mx-auto d-block cash_payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div
                            class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 d-flex justify-content-center align-items-center mt-5 plan-controls">
                            <div
                                class="mt-5 me-3 border border-gray-400 w-50 {{ $newPlan['amountToPay'] <= 0 ? 'd-none' : '' }}">
                                {{ Form::select('payment_type', $paymentTypes, \App\Models\Subscription::TYPE_STRIPE, ['class' => 'form-select form-select-solid', 'required', 'id' => 'paymentType', 'data-control' => 'select2']) }}
                            </div>
                            <div class="mt-5 stripePayment proceed-to-payment">
                                <button type="button" class="btn btn-primary rounded-pill mx-auto d-block makePayment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 paypalPayment proceed-to-payment d-none">
                                <button type="button"
                                    class="btn btn-primary rounded-pill mx-auto d-block paymentByPaypal"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 razorPayPayment proceed-to-razor-pay-payment d-none">
                                <button type="button"
                                    class="btn btn-primary rounded-pill mx-auto d-block razor_pay_payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 paystackPayPayment d-none">
                                <button type="button"
                                    class="btn btn-primary rounded-pill mx-auto d-block paystack-pay-payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                            <div class="mt-5 cashPayment proceed-cash-payment d-none">
                                <button type="button" class="btn btn-primary rounded-pill mx-auto d-block cash_payment"
                                    data-id="{{ $subscriptionsPricingPlan->id }}"
                                    data-plan-price="{{ $subscriptionsPricingPlan->price }}">
                                    {{ __('messages.subscription_plans.pay_or_switch_plan') }}</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row justify-content-center payment-attachments d-none">
                    <div class="col-xl-4 col-lg-6 col-md-8 col-sm-12 col-12 mt-5">
                        {{ Form::label('paymentAttachment', 'Attach Files' . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::file('payment_attachments[]', ['id' => 'paymentAttachment', 'class' => 'form-control', 'multiple' => true]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
