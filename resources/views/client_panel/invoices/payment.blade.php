@extends('client_panel.layouts.app')
@section('title')
    {{ __('messages.payment.add_payment') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <div class="card">
                {{ Form::open(['id'=>'clientPaymentForm', 'enctype' => 'multipart/form-data']) }}
                <div class="card-body">
                    <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                    {{ Form::hidden('invoice_id',$totalPayable['id'],['id'=>'invoice_id']) }}
                    <div class="row">
                        <div class="form-group col-sm-6 mb-5">
                            {{ Form::label('payable_amount',__('messages.payment.payable_amount').':', ['class' => 'form-label mb-3']) }}
                            <div class="input-group mb-5">
                                {{ Form::text('payable_amount', $totalPayable['total_amount'], ['id'=>'payable_amount','class' => 'form-control ','readonly']) }}
                                <a class="input-group-text bg-secondary cursor-default text-decoration-none" href="javascript:void(0)">
                                    <span>{{ getInvoiceCurrencySymbol($invoice->currency_id) }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 mb-5">
                            {{ Form::label('payment_type',__('messages.payment.payment_type').':', ['class' => 'form-label required mb-3']) }}
                            {{ Form::select('payment_type', $paymentType, null,['id'=>'payment_type','class' => 'form-select','placeholder'=> __('messages.payment.select_payment_type'),'required']) }}
                        </div>
                        <div class="form-group col-sm-6 mb-5 amount">
                            {{ Form::label('amount',__('messages.invoice.amount').':', ['class' => 'form-label required mb-3']) }}
                            {{ Form::number('amount', null, ['id'=>'amount','class' => 'form-control','step'=>'any','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','pattern'=>"^\d*(\.\d{0,2})?$",'required']) }}
                            <span id="error-msg" class="text-danger"></span>
                        </div>
                        <div class="form-group col-sm-6 mb-5">
                            {{ Form::label('payment_mode',__('messages.payment.payment_mode').':', ['class' => 'form-label required mb-3']) }}
                            {{ Form::select('payment_mode',$paymentMode, null,['id'=>'payment_mode','class' => 'form-select','placeholder'=> __('messages.payment.select_payment_mode'),'required']) }}
                        </div>
                        <div class="form-group col-sm-6 mb-5" id="transaction">
                            {{ Form::label('transactionId',__('messages.payment.transaction_id').':', ['class' => 'form-label mb-3']) }}
                            {{ Form::text('transaction_id', null, ['id'=>'transactionId','class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-12 mb-5">
                            {{ Form::label('notes',__('messages.invoice.note').':', ['class' => 'form-label required mb-3']) }}
                            {{ Form::textarea('notes', null, ['id'=>'payment_note','class' => 'form-control','rows'=>'5','required']) }}
                        </div>
                        <div class="mb-3 col-sm-3 payment-attachment d-none">
                            {{ Form::label('paymentAttachment','Attach File'.':', ['class' => 'form-label mb-3']) }}
                            {{ Form::file('payment_attachment', ['id'=>'paymentAttachment','class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    {{ Form::button(__('messages.common.pay'), ['type' => 'submit','class' => 'btn btn-primary me-2','id' => 'btnPay','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                    <a href="{{ route('client.invoices.index') }}" type="reset"
                       class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
