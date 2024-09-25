@extends('layouts.auth')
@section('title')
    {{ __('messages.client.reset_password') }}
@endsection
@section('content')
    <!--begin::Main-->
    <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
        <div class="col-12 text-center mt-0">
            <a href="{{ url('/') }}" class="image mb-7 mb-sm-10 image-medium">
                <img alt="Logo" src="{{ getLogoUrl() }}" class="img-fluid object-contain">
            </a>
        </div>
        <div class="width-540">
            @include('flash::message')
            @include('layouts.errors')
        </div>
        <div class="wbg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h1 class="text-center mb-7">{{ __('messages.client.reset_password') }}</h1>
            <form class="form w-100" method="POST" action="{{ route('client.password.update') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="id" value="{{ Crypt::decrypt($request->id) }}">
                <!-- Password -->
                <div class="fv-row mb-10">
                    <label class="form-label" for="password">{{ __('messages.client.reset_password') . ':' }}<span
                            class="required"></span></label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="off"
                        placeholder="{{ __('messages.client.reset_your_password') }}" />
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="fv-row mb-5">
                    <label class="form-label"
                        for="password_confirmation">{{ __('messages.client.confirm_password') . ':' }}<span
                            class="required"></span></label>
                    </label>
                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation"
                        autocomplete="off" placeholder="{{ __('messages.client.confirm_your_password') }}" />
                    <div class="invalid-feedback">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100 mb-5">
                        <span class="indicator-label">{{ __('Reset Password') }}</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!--end::Main-->
@endsection
