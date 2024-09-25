@extends('super_admin_settings.edit')
@section('title')
    {{ __('messages.general') }}
@endsection
@section('section')
    <div class="card">
        <div class="card-body">
            {{ Form::open(['route' => ['super.admin.settings.update'], 'method' => 'post', 'files' => true, 'id' => 'createSuperAdminSetting']) }}
            <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-5">
                        {{ Form::label('app_name', __('messages.setting.app_name') . ':', ['class' => 'form-label mb-3']) }}
                        <span class="required"></span>
                        {{ Form::text('app_name', $settings['app_name'], ['class' => 'form-control form-control-solid', 'maxLength' => 30, 'placeholder' => __('messages.setting.app_name')]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-5">
                        {{ Form::label('plan_expire_notification', __('messages.setting.plan_expire_notification') . ':', ['class' => 'form-label mb-3']) }}
                        <span class="required"></span>
                        {{ Form::number('plan_expire_notification', $settings['plan_expire_notification'], ['class' => 'form-control form-control-solid', 'maxLength' => 2, 'placeholder' => __('messages.setting.plan_expire_notification'), 'min' => '0', 'value' => '0', 'oninput' => "validity.valid||(value=value.replace(/[e\-]/gi,''))"]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-5">
                        {{ Form::label('home_page_support_link', __('messages.setting.home_page_support_link') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::text('home_page_support_link', $settings['home_page_support_link'], ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.setting.home_page_support_link')]) }}
                    </div>
                </div>
                <div class="col-md-3 mb-5">
                    {{ Form::label('currency_after_amount', __('messages.setting.currency_position') . ':', ['class' => 'form-label required mb-3']) }}
                    <label class="form-check form-switch form-check-custom mt-3">
                        <input class="form-check-input currencyAfterAmount" type="checkbox" name="currency_after_amount"
                            id="currencyAfterAmount"
                            {{ $settings['currency_after_amount'] == \App\Models\SuperAdminSetting::CURRENCY_AFTER_AMOUNT ? 'checked' : '' }}>
                        <span class="form-check-label text-gray-600"
                            for="currencyAfterAmount">{{ __('messages.setting.show_currency_behind') }}</span>&nbsp;&nbsp;
                    </label>
                </div>
                <div class="col-md-3 mb-5">
                    <div class="form-group mb-5">
                        {{ Form::label('vat_no_label', __('messages.setting.vat_no_label') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('vat_no_label', $settings['vat_no_label'] ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.setting.vat_no_label')]) }}
                    </div>
                </div>
                <?php
                $style = 'style=';
                $background = 'background-image:';
                ?>
                <!-- App Logo Field -->
                <div class="form-group col-sm-6 mb-5">
                    <div class="mb-3" io-image-input="true">
                        <label for="appLogoPreview"
                            class="form-label required">{{ __('messages.setting.app_logo') . ':' }}</label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="appLogoPreview"
                                    {{ $style }}"{{ $background }}
                                    url({{ $settings['app_logo'] ? asset($settings['app_logo']) : asset('assets/images/infyom..png') }}
                                    )">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.setting.change_app_logo') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                        <input type="file" id="superAdminAppLogo" name="app_logo"
                                            class="image-upload d-none" accept="image/*" />
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Favicon Field -->
                <div class="form-group col-sm-6 mb-5">
                    <div class="mb-3" io-image-input="true">
                        <label for="faviconPreview" class="form-label required">
                            {{ __('messages.setting.fav_icon') . ':' }}</label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="faviconPreview"
                                    {{ $style }}"{{ $background }}
                                    url({{ $settings['favicon_icon'] ? asset($settings['favicon_icon']) : asset('web/media/logos/favicon.ico') }}
                                    );">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.setting.change_favicon') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                        <input type="file" id="favicon" name="favicon_icon" class="image-upload d-none"
                                            accept="image/*" />
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header ps-3 border-bottom-1 border-0" data-bs-toggle="collapse" aria-expanded="true"
                    aria-controls="kt_account_profile_details">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('messages.payment-gateway') }}</h3>
                    </div>
                </div>
                <div id="kt_account_profile_details" class="collapse show">
                    <div class="row">
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('stripe_key', __('messages.setting.stripe_key') . ':', ['class' => 'form-label stripe-key-label mb-3']) }}
                            {{ Form::text('stripe_key', $settings['stripe_key'], ['class' => 'form-control stripe-key form-control-solid', 'placeholder' => __('messages.setting.stripe_key')]) }}
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('stripe_secret', __('messages.setting.stripe_secret') . ':', ['class' => 'form-label stripe-secret-label mb-3']) }}
                            {{ Form::text('stripe_secret', $settings['stripe_secret'], ['class' => 'form-control stripe-secret form-control-solid', 'placeholder' => __('messages.setting.stripe_secret')]) }}
                        </div>
                        <div class="form-group col-sm-2 mb-5 mt-10">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input feature" type="checkbox" name="stripe_enabled"
                                    {{ $settings['stripe_enabled'] == 1 ? 'checked' : '' }} id="stripe">
                                <span class="form-check-label fw-bold"
                                    for="stripe">{{ __('messages.setting.stripe') }}</span>&nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('paypal_client_id', __('messages.setting.paypal_client_id') . ':', ['class' => 'form-label paypal-client-id-label mb-3']) }}
                            {{ Form::text('paypal_client_id', $settings['paypal_client_id'], ['class' => 'form-control paypal-client-id form-control-solid', 'placeholder' => __('messages.setting.paypal_client_id')]) }}
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('paypal_secret', __('messages.setting.paypal_secret') . ':', ['class' => 'form-label paypal-secret-label mb-3']) }}
                            {{ Form::text('paypal_secret', $settings['paypal_secret'], ['class' => 'form-control paypal-secret form-control-solid', 'placeholder' => __('messages.setting.paypal_secret')]) }}
                        </div>
                        <div class="form-group col-sm-2 mb-5 mt-10">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input feature" type="checkbox" name="paypal_enabled" id="paypal"
                                    {{ $settings['paypal_enabled'] == 1 ? 'checked' : '' }}>
                                <span class="form-check-label fw-bold"
                                    for="paypal">{{ __('messages.setting.paypal') }}</span>&nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('razorpay_key', __('messages.setting.razorpay_key') . ':', ['class' => 'form-label razorpay-key-label mb-3']) }}
                            {{ Form::text('razorpay_key', $settings['razorpay_key'], ['class' => 'form-control razorpay-key form-control-solid', 'placeholder' => __('messages.setting.razorpay_key')]) }}
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('razorpay_secret', __('messages.setting.razorpay_secret') . ':', ['class' => 'form-label razorpay-secret-label mb-3']) }}
                            {{ Form::text('razorpay_secret', $settings['razorpay_secret'], ['class' => 'form-control razorpay-secret form-control-solid', 'placeholder' => __('messages.setting.razorpay_secret')]) }}
                        </div>
                        <div class="form-group col-sm-2 mb-5 mt-10">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input feature" type="checkbox" name="razorpay_enabled"
                                    id="razorpay" {{ $settings['razorpay_enabled'] == 1 ? 'checked' : '' }}>
                                <span class="form-check-label fw-bold"
                                    for="razorpay">{{ __('messages.setting.razorpay') }}</span>&nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('paystack_key', __('messages.setting.paystack_key') . ':', ['class' => 'form-label mb-3']) }}
                            {{ Form::text('paystack_key', $settings['paystack_key'] ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.setting.paystack_key')]) }}
                        </div>
                        <div class="form-group col-sm-5 mb-5">
                            {{ Form::label('paystack_secret', __('messages.setting.paystack_secret') . ':', ['class' => 'form-label mb-3']) }}
                            {{ Form::text('paystack_secret', $settings['paystack_secret'] ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.setting.paystack_secret')]) }}
                        </div>
                        <div class="form-group col-sm-2 mb-5 mt-10">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input feature" type="checkbox" name="paystack_enabled"
                                    id="paystack"
                                    {{ isset($settings['paystack_enabled']) && $settings['paystack_enabled'] == 1 ? 'checked' : '' }}>
                                <span class="form-check-label fw-bold"
                                    for="paystack">{{ __('messages.setting.paystack') }}</span>&nbsp;&nbsp;
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="float-end">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
                    {{ Form::reset(__('messages.common.cancel'), ['class' => 'btn btn-secondary btn-active-light-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
