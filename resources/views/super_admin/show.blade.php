@extends('layouts.app')
@section('title')
    {{ __('messages.super_admin.super_admin').' '. __('messages.common.details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                @if($superAdmin->id !== \App\Models\User::ADMIN)
                    <a href="{{route('super-admins.edit', $superAdmin->id)}}"
                       class="btn btn-primary me-4">{{__('messages.common.edit')}}</a>
                @endif
                <a href="{{route('super-admins.index')}}"
                   class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('super_admin.show_fields')
        </div>
    </div>
@endsection
