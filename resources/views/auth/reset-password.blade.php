@extends('layouts.auth')
@section('title')
    Reset Password
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

            <form class="form w-100" method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="fv-row mb-10">
                    <label class="form-label fs-6 fw-bolder text-dark" for="email">Email</label>
                    <input id="email" class="form-control form-control-lg "
                           value="{{ old('email', $request->email) }}"
                           type="email" name="email" required autocomplete="off" autofocus/>

                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>

                <!-- Password -->
                <div class="fv-row mb-10">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0" for="password">Password </label>
                    <input id="password" class="form-control form-control-lg "
                           type="password"
                           name="password"
                           required autocomplete="off"/>
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="fv-row mb-5">
                    <label class="form-label fw-bolder text-dark fs-6" for="password_confirmation">Confirm
                        Password</label>
                    <input class="form-control form-control-lg " type="password"
                           id="password_confirmation" name="password_confirmation" autocomplete="off"/>
                    <div class="invalid-feedback">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                        <span class="indicator-label">{{ __('Reset Password') }}</span>
                    </button>

                </div>

            </form>
        </div>
    </div>

    <!--end::Main-->
@endsection

