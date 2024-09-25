@extends('layouts.app')
@section('title')
    {{ __('messages.payment_qr_codes.payment_qr_codes') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:payment-qr-code-table />
        </div>
    </div>
    @include('payment_qr_codes.create_modal')
    @include('payment_qr_codes.edit_modal')
@endsection
