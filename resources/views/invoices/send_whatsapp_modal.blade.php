<div id="sendWhatsAppModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.invoice.send_invoice_in_whatsapp') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'sendInvoiceOnWhatsApp']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                <div class="row">
                    {{ Form::hidden('invoice_id', null, ['id' => 'invoiceId']) }}
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('phone_number', __('messages.invoice.phone_number') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::tel('phone_number', getSettingValue('country_code'), ['class' => 'form-control whatsapp-phone-number', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'id' => 'phoneNumber', 'required']) }}
                        {{ Form::hidden('region_code', null, ['id' => 'prefix_code']) }}
                        <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓
                            {{ __('messages.placeholder.valid_number') }}</span>
                        <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.invoice.send_whatsapp'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'btnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
