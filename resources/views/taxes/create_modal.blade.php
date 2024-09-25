<div id="addTaxModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.tax.add_tax') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addTaxForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('name', __('messages.common.name') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.common.name')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('value', __('messages.common.value') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::number('value', null, ['id' => 'value', 'class' => 'form-control form-control-solid', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'max' => '100', 'value' => '0', 'step' => '.01', 'required', 'placeholder' => __('messages.common.value')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('is_default', __('messages.tax.is_default') . ':', ['class' => 'form-label mb-3']) }}
                        <div class="form-check form-check-custom form-check-solid">
                            <div class="btn-group">
                                <input class="form-check-input me-2" type="radio" name="is_default" value="1"
                                    id="flexRadioDefault" />
                                <label class="form-check-label" for="flexRadioDefault">
                                    {{ __('messages.tax.yes') }}
                                </label>
                                <input class="form-check-input mx-2" type="radio" name="is_default" value="0"
                                    id="flexRadioDefault" checked />
                                <label class="form-check-label" for="flexRadioDefault">
                                    {{ __('messages.tax.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'btnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
