<div id="addPaymentQrCodeModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment_qr_codes.add_qr_code') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addPaymentQrCodeForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('title', __('messages.payment_qr_codes.title') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.payment_qr_codes.title')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <div class="col-sm-12">
                            {{ Form::label('exampleInputImage', __('messages.payment_qr_codes.qr_image') . ':', ['class' => 'form-label qrImage required mb-3']) }}
                        </div>
                        <div class="col-sm-12">
                            <div class="image-picker">
                                <div class="image previewImage" id="paymentQrCodeInputImage">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                    title="{{ __('messages.payment_qr_codes.qr_image') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="qrCodeImageIcon"></i>
                                        <input type="file" id="qrCodeImage" name="qr_image"
                                            class="image-upload d-none" accept="image/*" />
                                        {{ Form::hidden('avatar_remove') }}
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-2 ', 'id' => 'btnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button"
                    class="btn btn-secondary btn-active-light-primary paymentQrCodeModelCancelButton"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
