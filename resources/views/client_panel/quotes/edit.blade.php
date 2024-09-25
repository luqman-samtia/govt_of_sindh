@extends('client_panel.layouts.app')
@section('title')
    {{ __('messages.quote.edit_quote') }}
@endsection
@section('header_toolbar')
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
                        {{ Form::model($quote, ['route' => ['client.quotes.update', $quote->id], 'id' => 'clientQuoteEditForm']) }}
                        @include('client_panel.quotes.edit_fields')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('quotes.templates.templates')
    {{ Form::hidden('quote_update_url',route('client.quotes.update', $quote->id),['id' => 'clientQuoteUpdateUrl']) }}
    {{ Form::hidden('quote_url',route('quotes.index'),['id' => 'quoteUrl']) }}
    {{ Form::hidden('quote_id',$quote->id,['id' => 'clientQuoteId']) }}
    {{ Form::hidden('clients',json_encode($clients, true),['id' => 'clients']) }}
    {{ Form::hidden('products',json_encode($associateProducts, true),['id' => 'products']) }}
    {{ Form::hidden('unique_id',$quote->quoteItems->count() + 1 ,['id' => 'uniqueId']) }}
    {{ Form::hidden('quote_note',isset($quote->note) ? true : false ,['id' => 'quoteNoteData']) }}
    {{ Form::hidden('quote_term',isset($quote->term) ? true : false ,['id' => 'quoteTermData']) }}
    {{ Form::hidden('quote_recurring',isset($quote->recurring) ? true : false ,['id' => 'invoiceRecurring']) }}
    {{ Form::hidden('thousand_separator',getSettingValue('thousand_separator') ,['id' => 'thousandSeparator']) }}
    {{ Form::hidden('decimal_separator',getSettingValue('decimal_separator') ,['id' => 'decimalSeparator']) }}
    {{ Form::hidden('edit_due_date',$quote->due_date ,['id' => 'editQuoteDueDate']) }}
@endsection
