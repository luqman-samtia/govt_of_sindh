@extends('layouts.app')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @yield('section')
        </div>
    </div>
    {{ Form::hidden('image_validation', __('messages.setting.image_validation') ,['id' => 'imageValidation']) }}
@endsection
