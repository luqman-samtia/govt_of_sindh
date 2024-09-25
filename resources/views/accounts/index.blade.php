@extends('layouts.app')
@section('title')
    {{ __('messages.accounts.accounts') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:account-table />
        </div>
    </div>
    @include('accounts.create_modal')
    @include('accounts.edit_modal')
@endsection
