@extends('layouts.app')
@section('title')
    {{ __('messages.payment-gateway') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['payment-gateway.store'], 'method' => 'post', 'files' => true, 'id' => 'createPaymentGateway']) }}
                <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('stripe_key', __('messages.setting.stripe_key') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::text('stripe_key', $paymentGateway['stripe_key'] ?? null, ['class' => 'form-control  stripe-key form-control-solid', 'placeholder' => __('messages.setting.stripe_key')]) }}
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('stripe_secret', __('messages.setting.stripe_secret') . ':', ['class' => 'form-label stripe-secret-label mb-3']) }}
                        {{ Form::text('stripe_secret', $paymentGateway['stripe_secret'] ?? null, ['class' => 'form-control stripe-secret form-control-solid', 'placeholder' => __('messages.setting.stripe_secret')]) }}
                    </div>
                    <div class="form-group col-sm-2 mb-5 mt-4 mt-sm-10">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input feature" type="checkbox" name="stripe_enabled"
                                {{ $paymentGateway['stripe_enabled'] == 1 ? 'checked' : '' }} id="stripe">
                            <span class="form-check-label fw-bold"
                                for="stripe">{{ __('messages.setting.stripe') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('paypal_client_id', __('messages.setting.paypal_client_id') . ':', ['class' => 'form-label paypal-client-id-label mb-3']) }}
                        {{ Form::text('paypal_client_id', $paymentGateway['paypal_client_id'] ?? null, ['class' => 'form-control  paypal-client-id form-control-solid', 'placeholder' => __('messages.setting.paypal_client_id')]) }}
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('paypal_secret', __('messages.setting.paypal_secret') . ':', ['class' => 'form-label paypal-secret-label mb-3']) }}
                        {{ Form::text('paypal_secret', $paymentGateway['paypal_secret'] ?? null, ['class' => 'form-control paypal-secret form-control-solid', 'placeholder' => __('messages.setting.paypal_secret')]) }}
                    </div>
                    <div class="form-group col-sm-2 mb-5 mt-4 mt-sm-10">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input feature" type="checkbox" name="paypal_enabled" id="paypal"
                                {{ $paymentGateway['paypal_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="form-check-label fw-bold"
                                for="paypal">{{ __('messages.setting.paypal') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('razorpay_key', __('messages.setting.razorpay_key') . ':', ['class' => 'form-label razorpay-key-label mb-3']) }}
                        {{ Form::text('razorpay_key', $paymentGateway['razorpay_key'] ?? null, ['class' => 'form-control razorpay-key form-control-solid', 'placeholder' => __('messages.setting.razorpay_key')]) }}
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('razorpay_secret', __('messages.setting.razorpay_secret') . ':', ['class' => 'form-label razorpay-secret-label mb-3']) }}
                        {{ Form::text('razorpay_secret', $paymentGateway['razorpay_secret'] ?? null, ['class' => 'form-control razorpay-secret form-control-solid', 'placeholder' => __('messages.setting.razorpay_secret')]) }}
                    </div>
                    <div class="form-group col-sm-2 mb-5 mt-4 mt-sm-10">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input feature" type="checkbox" name="razorpay_enabled" id="razorpay"
                                {{ $paymentGateway['razorpay_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="form-check-label fw-bold"
                                for="razorpay">{{ __('messages.setting.razorpay') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('paystack_key', __('messages.setting.paystack_key') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::text('paystack_key', $paymentGateway['paystack_key'] ?? null, ['class' => 'form-control razorpay-key form-control-solid', 'placeholder' => __('messages.setting.paystack_key')]) }}
                    </div>
                    <div class="form-group col-sm-5 mb-5">
                        {{ Form::label('razorpay_secret', __('messages.setting.paystack_secret') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::text('paystack_secret', $paymentGateway['paystack_secret'] ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.setting.paystack_secret')]) }}
                    </div>
                    <div class="form-group col-sm-2 mb-5 mt-4 mt-sm-10">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input feature" type="checkbox" name="paystack_enabled" id="paystack"
                                {{ isset($paymentGateway['paystack_enabled']) && $paymentGateway['paystack_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="form-check-label fw-bold"
                                for="paystack">{{ __('messages.setting.paystack') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="float-end">
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
                            {{ Form::reset(__('messages.common.cancel'), ['class' => 'btn  btn-secondary btn-active-light-primary']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
