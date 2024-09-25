@extends('layouts.app')
@section('title')
    {{ __('messages.cookie_warning') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors')
            {{ Form::open(['route' => ['cookie.warning.update'], 'method' => 'POST']) }}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            {{ Form::label('text_main', __('messages.cookie_warning') . ':', ['class' => 'form-label mb-3']) }}
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-sm-0 mb-4">
                            <div>
                                <input class="form-check-input" type="radio" name="show_cookie" value="1"
                                    id="showCookieYes" {{ $showCookie->value == 1 ? 'checked' : '' }}>
                                <label class="form-check-label mx-0 me-2" for="showCookieYes">
                                    {{ __('messages.tax.yes') }}
                                </label>
                                <input class="form-check-input " type="radio" name="show_cookie" value="0"
                                    id="showCookieNo" {{ $showCookie->value == 0 ? 'checked' : '' }}>
                                <label class="form-check-label mx-1" for="showCookieNo">
                                    {{ __('messages.tax.no') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 mb-5">
                            {{ Form::textarea('cookie_warning', $cookieWarning->value ?? null, ['class' => 'form-control form-control-solid', 'rows' => '5', 'placeholder' => __('messages.cookie_warning')]) }}
                        </div>
                    </div>
                    <div class="float-end">
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
