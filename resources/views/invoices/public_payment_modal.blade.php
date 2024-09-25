<div id="publicPaymentModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment.add_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'publicPaymentForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="publicPaymentValidationErrorsBox"></div>
                <div class="row">
                    {{ Form::hidden('invoice_id', $invoice->id, ['id' => 'invoice_id']) }}
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('payable_amount', __('messages.payment.payable_amount') . ':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group mb-5">
                            {{ Form::text('payable_amount', $dueAmount ?? null, ['id' => 'payable_amount', 'class' => 'form-control ', 'readonly']) }}
                            <a class="input-group-text bg-secondary cursor-default text-decoration-none"
                                href="javascript:void(0)">
                                <span>{{ getInvoiceCurrencySymbol($invoice->currency_id) }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('payment_type', __('messages.payment.payment_type') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::select('payment_type', $paymentTypes, null, ['id' => 'payment_type', 'class' => 'form-select', 'placeholder' => __('messages.payment.select_payment_type'), 'required']) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5 amount">
                        {{ Form::label('amount', __('messages.invoice.amount') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::number('amount', null, ['id' => 'amount', 'class' => 'form-control', 'step' => 'any', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'pattern' => "^\d*(\.\d{0,2})?$", 'required']) }}
                        <span id="error-msg" class="text-danger"></span>
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('payment_mode', __('messages.payment.payment_mode') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::select('payment_mode', $paymentModes, null, ['id' => 'payment_mode', 'class' => 'form-select', 'placeholder' => __('messages.payment.select_payment_mode'), 'required']) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5" id="transaction">
                        {{ Form::label('transactionId', __('messages.payment.transaction_id') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::text('transaction_id', null, ['id' => 'transactionId', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('notes', __('messages.invoice.note') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::textarea('notes', null, ['id' => 'payment_note', 'class' => 'form-control', 'rows' => '5', 'required']) }}
                    </div>
                    <div class="mb-3 col-sm-6 payment-attachment d-none">
                        {{ Form::label('paymentAttachment', 'Attach File' . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::file('payment_attachment', ['id' => 'paymentAttachment', 'class' => 'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.pay'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'btnPublicPay', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
