@extends('layouts.app')
@section('title')
    Transactions
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column  ">
            @include('flash::message')
            <livewire:transaction-table/>
        </div>
    </div>
    @include('transactions.payment-notes-modal')
    {{ Form::hidden('currency', getCurrencySymbol(),['id' => 'currency']) }}
@endsection
