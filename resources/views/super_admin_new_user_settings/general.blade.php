@extends('super_admin_new_user_settings.edit')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('section')
    @php $styleCss = 'style'; @endphp
    <div class="card">
        <div class="card-body">
            {{ Form::open(['route' => ['new-user-settings.update'], 'method' => 'post', 'files' => true, 'id' => 'newUserSettingForm']) }}
            <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
            <div class="row">
                <div class="form-group col-lg-4 col-md-6 mb-5">
                    {{ Form::label('app_name', __('messages.setting.app_name') . ':', ['class' => 'form-label required mb-3']) }}
                    {{ Form::text('app_name', $data['app_name'] ?? null, ['class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.setting.app_name')]) }}
                </div>
                <div class="form-group col-lg-4 col-md-6 mb-5">
                    {{ Form::label('company_name', __('messages.setting.company_name') . ':', ['class' => 'form-label required mb-3']) }}
                    {{ Form::text('company_name', $data['company_name'] ?? null, ['class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.setting.company_name')]) }}
                </div>

                <div class="form-group col-lg-2 col-md-6 mb-5 country-code">
                    {{ Form::label('country_phone', __('messages.setting.country_code') . ':', ['class' => 'form-label mb-3']) }}
                    {{ Form::tel('country_phone', null, ['class' => 'form-control width-0', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'id' => 'newUserCountryPhone']) }}
                    {{ Form::hidden('country_code', $data['country_code'] ?? null, ['id' => 'newUserCountryCode']) }}
                </div>

                <div class="form-group col-lg-4 col-md-6 mb-5">
                    {{ Form::label('company_phone', __('messages.setting.company_phone') . ':', ['class' => 'form-label required mb-3']) }}
                    <br>
                    {{ Form::tel('company_phone', $data['company_phone'] ?? null, ['class' => 'form-control form-control-solid', 'id' => 'newUserPhoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'required']) }}
                    {{ Form::hidden('prefix_code', $data['prefix_code'] ?? null, ['id' => 'newUserPrefixCode']) }}
                    <span id="valid-msg" class="hide">✓ &nbsp; {{ __('messages.placeholder.valid_number') }}</span>
                    <span id="error-msg" class="hide"></span>
                </div>

                <div class="form-group col-lg-4 col-md-6 mb-5">
                    {{ Form::label('date_format', __('messages.setting.date_format') . ':', ['class' => 'form-label required mb-3']) }}
                    {{ Form::select('date_format', $dateFormats, $data['date_format'] ?? null, ['class' => 'form-select form-select-solid', 'id' => 'dateFormat']) }}
                </div>
                <div class="form-group col-lg-4 col-md-6 mb-5">
                    {{ Form::label('timezone', __('messages.setting.timezone') . ':', ['class' => 'form-label required mb-3']) }}
                    {{ Form::select('time_zone', $timezones, $data['time_zone'] ?? null, ['class' => 'form-select form-select-solid', 'id' => 'timeZone']) }}
                </div>
                <div class="form-group col-lg-2 col-md-4 mb-5">
                    <div io-image-input="true">
                        <label for="appLogoPreview"
                            class="form-label required">{{ __('messages.setting.app_logo') . ':' }}</label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="appLogoPreview"
                                    {{ $styleCss }}="background-image: url('{{ $data['app_logo'] ?? asset('assets/images/infyom.png') }}')">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.setting.change_app_logo') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="appLogoIcon"></i>
                                        <input type="file" id="appLogo" name="app_logo" class="image-upload d-none"
                                            accept="image/*" />
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Company Logo Field -->
                <div class="form-group col-lg-2 col-md-4 mb-5">
                    <div io-image-input="true">
                        <label for="faviconPreview" class="form-label required">
                            {{ __('messages.setting.fav_icon') . ':' }}</label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="faviconPreview"
                                    {{ $styleCss }}="background-image: url('{{ $data['favicon_icon'] ?? asset('assets/images/favicon.png') }}');">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.setting.change_favicon') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="faviconImageIcon"></i>
                                        <input type="file" id="favicon_icon" name="favicon_icon"
                                            class="image-upload d-none" accept="image/*" />
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="text-end">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
                        <a href="{{ route('new-user-settings.edit') }}"
                            class="btn  btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    @endsection
