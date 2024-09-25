<div id="editPaymentQrCodeModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment_qr_codes.edit_qr_code') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editPaymentQrCodeForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                <input type="hidden" name="paymentQrCodeId" id="paymentQrCodeId">
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('title', __('messages.payment_qr_codes.title') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('title', null, ['id' => 'editQrCodeTitle', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.payment_qr_codes.title')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <div class="col-sm-12">
                            {{ Form::label('exampleInputImage', __('messages.payment_qr_codes.qr_image') . ':', ['class' => 'form-label qrImage required mb-3']) }}
                        </div>
                        <div class="col-sm-12">
                            <div class="image-picker">
                                <div class="image previewImage qr_code_image" id="exampleInputImage">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.payment_qr_codes.qr_image') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="qrCodeImageIcon"></i>
                                        <input type="file" id="qr_image" name="qr_image" class="image-upload d-none"
                                            accept="image/*" />
                                        {{ Form::hidden('avatar_remove') }}
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'btnEditSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
