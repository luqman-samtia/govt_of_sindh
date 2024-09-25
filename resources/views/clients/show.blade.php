@extends('layouts.app')
@section('title')
    {{ __('messages.client.client_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{route('clients.edit', $client->id)}}"
                   class="btn btn-primary me-4">{{__('messages.common.edit')}}</a>
                <a href="{{route('clients.index')}}"
                   class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            @include('clients.show_fields')
        </div>
    </div>
@endsection
