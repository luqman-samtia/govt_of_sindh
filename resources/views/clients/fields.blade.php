<div class="row gx-10 mb-5">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('first_name', __('messages.client.first_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('first_name', null, ['class' => 'form-control form-control-solid user-first-name', 'placeholder' => __('messages.client.first_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('last_name', __('messages.client.last_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('last_name', null, ['class' => 'form-control form-control-solid user-last-name', 'placeholder' => __('messages.client.last_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5 position-relative">
            {{ Form::label('email', __('messages.client.email') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::text('email', null, ['class' => 'form-control form-control-solid search-email user-email', 'placeholder' => __('messages.client.email'), 'autocomplete' => 'off']) }}
            <ul class="form-select search-email-list"></ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('contact', __('messages.client.contact_no') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::tel('contact', null, ['class' => 'form-control form-control-solid user-contact-number', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'id' => 'phoneNumber']) }}
            {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
            <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓
                {{ __('messages.placeholder.valid_number') }}</span>
            <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
        </div>
    </div>
    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('password', __('messages.client.password') . ':', ['class' => 'form-label mb-3']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid password" type="password"
                        placeholder="{{ __('messages.client.password') }}" name="password" autocomplete="off"
                        aria-label="Password" data-toggle="password">
                    <span
                        class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('confirmPassword', __('messages.client.confirm_password') . ':', ['class' => 'form-label mb-3']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid c-password" type="password"
                        placeholder="{{ __('messages.client.confirm_password') }}" name="password_confirmation"
                        autocomplete="off" aria-label="Password" data-toggle="password">
                    <span
                        class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('website', __('messages.client.website') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::text('website', null, ['class' => 'form-control form-control-solid website', 'placeholder' => __('messages.client.website')]) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('postal_code', __('messages.client.postal_code') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('postal_code', null, ['class' => 'form-control form-control-solid postal-code', 'placeholder' => __('messages.client.postal_code'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-5">
            {{ Form::label('country', __('messages.client.country') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::select('country_id', $countries, null, ['id' => 'countryId', 'class' => 'form-select form-select-solid country', 'placeholder' => __('messages.client.country'), 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-5">
            {{ Form::label('state', __('messages.client.state') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::select('state_id', [], null, ['id' => 'stateId', 'class' => 'form-select form-select-solid state', 'placeholder' => __('messages.client.state'), 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-5">
            {{ Form::label('city', __('messages.client.city') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::select('city_id', [], null, ['id' => 'cityId', 'class' => 'form-select form-select-solid city', 'placeholder' => __('messages.client.city'), 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('address', __('messages.client.address') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::textarea('address', null, ['class' => 'form-control form-control-solid address', 'placeholder' => __('messages.client.address'), 'rows' => '5']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('notes', __('messages.client.notes') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::textarea('note', null, ['class' => 'form-control form-control-solid note', 'placeholder' => __('messages.client.notes'), 'rows' => '5']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('company_name', __('messages.setting.company_name') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::text('company_name', null, ['class' => 'form-control form-control-solid company-name', 'placeholder' => __('messages.setting.company_name')]) }}
        </div>
    </div>
    <div class="col-xl-3 col-lg-2">
        <div class="mb-3" io-image-input="true">
            <label for="exampleInputImage" class="form-label">{{ __('messages.client.profile') . ':' }}</label>
            <div class="d-block">
                <div class="image-picker">
                    <div class="image previewImage" id="clientProfileImage"
                        style="background-image: url('{{ !empty($client->profile_image) ? $client->profile_image : asset('assets/images/avatar.png') }}')">
                    </div>
                    <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                        title="{{ __('messages.user.change_profile') }}">
                        <label>
                            <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                            <input type="file" id="profile_image" name="profile" class="image-upload d-none"
                                accept="image/*" />
                        </label>
                    </span>
                </div>
            </div>
            <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4">
        <div class="mb-5">
            {{ Form::label('vat_no', $vatNoLabel . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::text('vat_no', null, ['class' => 'form-control form-control-solid vat-no', 'placeholder' => $vatNoLabel]) }}
        </div>
    </div>
</div>
<div class="float-end d-flex mt-5">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
    <a href="{{ route('clients.index') }}" type="reset"
        class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
</div>
