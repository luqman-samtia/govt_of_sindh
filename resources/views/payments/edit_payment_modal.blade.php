<div id="editPaymentModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment.edit_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'editPaymentForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('invoice',__('messages.invoice.invoice').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('invoice_id',null,['id'=>'edit_invoice_id','class' => 'form-control form-control-solid','readonly']) }}
                        {{ Form::hidden('invoice',null,['id'=>'invoice']) }}
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 amount d-block">
                        {{ Form::label('due_amount',__('messages.invoice.due_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('due_amount', null, ['id'=>'totalDue_amount','class' => 'form-control form-control-solid','placeholder'=>__('messages.invoice.due_amount'),'readonly','disabled']) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none edit-invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 amount d-block">
                        {{ Form::label('paid_amount',__('messages.invoice.paid_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('paid_amount', null, ['id'=>'totalPaid_amount','class' => 'form-control form-control-solid','placeholder'=>__('messages.invoice.paid_amount'),'readonly','disabled']) }}
                        <a class="input-group-text bg-secondary border-0 text-decoration-none edit-invoice-currency-code" id="autoCode" href="javascript:void(0)"
                           data-toggle="tooltip"
                           data-placement="right" title="Currency Code">
                            {{ getCurrencySymbol() }}
                        </a>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('payment_date',__('messages.payment.payment_date').(':'),['class' => 'form-label required mb-3']) }}
                        {{ Form::text('payment_date', null, ['class' => 'form-control form-control-solid ', 'id' => 'edit_payment_date','placeholder'=>__('messages.payment.payment_date'), 'autocomplete' => 'off','required','data-focus'=>"false"]) }}
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 amount d-block">
                        {{ Form::label('amount',__('messages.invoice.amount').':', ['class' => 'form-label required mb-3']) }}
                        <div class="input-group">
                            {{ Form::number('amount', null, ['id'=>'edit_amount','class' => 'form-control form-control-solid','step'=>'any','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','required','placeholder'=>__('messages.invoice.amount')]) }}
                        <a class="input-group-text bg-secondary border-0 text-decoration-none edit-invoice-currency-code" id="autoCode" href="javascript:void(0)"
                           data-toggle="tooltip"
                           data-placement="right" title="Currency Code">
                            {{ getCurrencySymbol() }}
                        </a>
                    </div>
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('payment_mode',__('messages.payment.payment_mode').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('payment_mode','Cash',['id'=>'edit_payment_mode','readonly','class' => 'form-control form-control-solid ']) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('notes',__('messages.invoice.note').':', ['class' => 'form-label required fs-6 text-gray-700 mb-3']) }}
                        {{ Form::textarea('notes', null, ['id'=>'edit_payment_note','class' => 'form-control form-control-solid','rows'=>'5','required']) }}
                    </div>
                    {{ Form::hidden('paymentId',null,['id'=>'paymentId']) }}
                    {{ Form::hidden('transactionId',null,['id'=>'transactionId']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.pay'), ['type' => 'submit','class' => 'btn btn-primary me-2','id' => 'editFormButton','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
