@extends('layouts.app')
@section('title')
    {{ __('messages.invoice.invoice_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                @if (!in_array($invoice->status, [\App\Models\Invoice::PAID, \App\Models\Invoice::PARTIALLY]))
                    <a href="{{ route('invoices.edit', ['invoice' => $invoice->id]) }}" class="btn btn-primary me-4"
                        data-turbo="false">{{ __('messages.common.edit') }}</a>
                @endif
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('invoices.show_fields', ['isPublicView' => true])
            @include('transactions.payment-notes-modal')
        </div>
    </div>
@endsection
