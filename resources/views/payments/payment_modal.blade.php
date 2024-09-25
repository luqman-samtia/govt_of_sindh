<div id="paymentModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment.add_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'paymentForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('invoice',__('messages.invoice.invoice').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::select('invoice_id', $invoice, null,['id'=>'adminPaymentInvoiceId','class' => 'form-select form-select-solid invoice ','placeholder'=>__('messages.invoice.invoice'),'required','data-control' =>'select2']) }}
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('due_amount',__('messages.invoice.due_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('due_amount', null, ['id'=>'due_amount','class' => 'form-control form-control-solid','placeholder'=>__('messages.invoice.due_amount'),'readonly','disabled']) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('paid_amount',__('messages.invoice.paid_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('paid_amount', null, ['id'=>'paid_amount','class' => 'form-control form-control-solid','placeholder'=>__('messages.invoice.paid_amount'),'readonly','disabled']) }}
                        <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                           data-toggle="tooltip"
                           data-placement="right" title="Currency Code">
                            {{ getCurrencySymbol() }}
                        </a>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                    {{ Form::label('payment_date', __('messages.payment.payment_date').(':'),['class' => 'form-label required mb-3']) }}
                    {{ Form::text('payment_date', null, ['class' => 'form-select', 'id' => 'payment_date', 'autocomplete' => 'off','required','data-focus'=>"false"]) }}
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('amount',__('messages.invoice.amount').':', ['class' => 'form-label required mb-3']) }}
                        <div class="input-group">
                            {{ Form::number('amount', null, ['id'=>'amount','class' => 'form-control form-control-solid amount d-flex','step'=>'any','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','pattern'=>"^\d*(\.\d{0,2})?$",'required','placeholder'=>__('messages.invoice.amount')]) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('payment_mode',__('messages.payment.payment_mode').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('payment_mode','Cash',['id'=>'adminPaymentMode','readonly','class' => 'form-control form-control-solid ']) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('notes',__('messages.invoice.note').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::textarea('notes', null, ['id'=>'payment_note','class' => 'form-control form-control-solid','rows'=>'5','required']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.pay'), ['type' => 'submit','class' => 'btn btn-primary me-2','id' => 'btnPay','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
