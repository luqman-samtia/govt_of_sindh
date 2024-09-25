@extends('layouts.app')
@section('title')
    {{ __('messages.invoices') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:invoice-table />
        </div>
    </div>
    @include('invoices.templates.templates')
    @include('invoices.send_whatsapp_modal')
    {{ Form::hidden('currency', getCurrencySymbol(), ['id' => 'currency']) }}
    {{ Form::hidden('status', $status, ['id' => 'status']) }}
@endsection
