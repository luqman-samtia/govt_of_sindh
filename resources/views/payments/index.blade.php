@extends('layouts.app')
@section('title')
    {{__('messages.payments')}}
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column  ">
        @include('flash::message')
        <livewire:admin-payment-table/>
    </div>
</div>
    @include('payments.payment_modal')
    @include('payments.edit_payment_modal')
    {{ Form::hidden('currency', getCurrencySymbol(),['id' => 'currency']) }}
@endsection
