@extends('layouts.app')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('layouts.errors')
            @include('flash::message')
            @yield('section')
        </div>
    </div>
    @include('settings.invoices.templates')
    {{ Form::hidden('is_edit', true, ['id' => 'isEdit']) }}
    {{ Form::hidden('image_validation', __('messages.setting.image_validation'), ['id' => 'imageValidation']) }}
    {{ Form::hidden('company_image_validation', __('messages.setting.company_image_validation'), ['id' => 'companyImageValidation']) }}
@endsection
