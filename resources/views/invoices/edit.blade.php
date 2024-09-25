@extends('layouts.app')
@section('title')
    {{ __('messages.invoice.edit_invoice') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <h1>@yield('title')</h1>
                    <a class="btn btn-outline-primary float-end"
                       href="{{ url()->previous() }}">{{ __('messages.common.back') }}</a>
                </div>
                <div class="col-12">
                    @include('layouts.errors')
                </div>
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($invoice, ['route' => ['invoices.update', $invoice->id], 'id' => 'invoiceEditForm']) }}
                        @include('invoices.edit_fields')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('invoices.templates.templates')
    {{ Form::hidden('invoice_update_url',route('invoices.update', ['invoice' => $invoice->id]),['id' => 'invoiceUpdateUrl']) }}
    {{ Form::hidden('invoice_url',route('invoices.index'),['id' => 'invoiceUrl']) }}
    {{ Form::hidden('invoice_id',$invoice->id,['id' => 'invoiceId']) }}
    {{ Form::hidden('clients',json_encode($clients, true),['id' => 'clients']) }}
    {{ Form::hidden('products',json_encode($associateProducts, true),['id' => 'products']) }}
    {{ Form::hidden('taxes',json_encode($associateTaxes, true),['id' => 'taxes']) }}
    {{ Form::hidden('unique_id',$invoice->invoiceItems->count() + 1 ,['id' => 'uniqueId']) }}
    {{ Form::hidden('invoice_note',isset($invoice->note) ? true : false ,['id' => 'invoiceNote']) }}
    {{ Form::hidden('invoice_term',isset($invoice->term) ? true : false ,['id' => 'invoiceTerm']) }}
    {{ Form::hidden('invoice_recurring',isset($invoice->recurring) ? true : false ,['id' => 'invoiceRecurring']) }}
    {{ Form::hidden('thousand_separator',getSettingValue('thousand_separator') ,['id' => 'thousandSeparator']) }}
    {{ Form::hidden('decimal_separator',getSettingValue('decimal_separator') ,['id' => 'decimalSeparator']) }}
    {{ Form::hidden('default_tax',json_encode($defaultTax, true) ,['id' => 'defaultTax']) }}
    {{ Form::hidden('edit_due_date',$invoice->due_date ,['id' => 'editDueDate']) }}
@endsection
