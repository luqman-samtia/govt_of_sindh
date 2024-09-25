@extends('layouts.auth')
@section('title')
    {{__(' Forgot Password')}}
@endsection
@section('content')
    @php
        $settingValue = getSuperAdminSettingValue();
    @endphp

    <div class="d-flex flex-column flex-column-fluid align-items-center mt-12 p-4">
        <div class="col-12 text-center mt-0">
            <a href="{{ url('/') }}" class="image mb-7 mb-sm-10 image-medium">
                <img alt="Logo" src="{{ asset($settingValue['app_logo']['value']) }}" class="img-fluid object-contain">
            </a>
        </div>
        <div class="width-540">
            @include('layouts.errors')
            @if(Session::has('status'))
                <div class="alert alert-success custom-message">
                    <div class="d-flex text-white align-items-center">
                        <svg class="svg-inline--fa fa-face-smile me-5" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="face-smile" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM164.1 325.5C158.3 318.8 148.2 318.1 141.5 323.9C134.8 329.7 134.1 339.8 139.9 346.5C162.1 372.1 200.9 400 255.1 400C311.1 400 349.8 372.1 372.1 346.5C377.9 339.8 377.2 329.7 370.5 323.9C363.8 318.1 353.7 318.8 347.9 325.5C329.9 346.2 299.4 368 255.1 368C212.6 368 182 346.2 164.1 325.5H164.1zM176.4 176C158.7 176 144.4 190.3 144.4 208C144.4 225.7 158.7 240 176.4 240C194 240 208.4 225.7 208.4 208C208.4 190.3 194 176 176.4 176zM336.4 240C354 240 368.4 225.7 368.4 208C368.4 190.3 354 176 336.4 176C318.7 176 304.4 190.3 304.4 208C304.4 225.7 318.7 240 336.4 240z"></path></svg><!-- <i class="fa-solid fa-face-smile  me-5"></i> Font Awesome fontawesome.com -->
                        <div>
                            <span class="text-white">{{ __('messages.user.we_have_emailed') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h1 class="text-center mb-7">{{ __('messages.user.forgot_password')}}</h1>
            <div class="fw-bold fs-4 mb-5 text-center">{{ __('messages.user.enter_your_email_to_reset')}}</div>
            <div class="fs-4 text-center mb-5">{{ __('messages.user.no_problem') }}</div>
            <form class="form w-100" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="row">
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            {{ __('messages.user.email').':' }}<span class="required"></span>
                        </label>
                        <input id="email" class="form-control" type="email"
                               value="{{ old('email') }}"
                               required autofocus name="email" autocomplete="off" placeholder="{{ __('messages.user.email') }}"/>
                    </div>
                </div>
                <div class="row">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12 d-flex text-start align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label"> {{ __('messages.user.email_password_reset_link') }}</span>
                        </button>
                        <a href="{{ route('login') }}"
                           class="btn btn-secondary my-0 ms-5 me-0">{{__('messages.common.cancel')}}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
