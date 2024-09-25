@extends('client_panel.layouts.app')
@section('title')
    Transactions
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:client-transaction-table/>
        </div>
    </div>
    @include('transactions.payment-notes-modal')
    {{ Form::hidden('currency', getCurrencySymbol() ,['id' => 'currency']) }}
@endsection
