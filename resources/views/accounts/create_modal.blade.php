<div id="addBankAccountModel" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.accounts.add_account') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addBankAccountForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-lg-6 col-sm-12 mb-5">
                        {{ Form::label('holder_name', __('messages.accounts.holder_name') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('holder_name', null, ['id' => 'title', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.accounts.holder_name')]) }}
                    </div>
                    <div class="form-group col-lg-6 col-sm-12 mb-5 ">
                        {{ Form::label('bank_name', __('messages.accounts.bank_name') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('bank_name', null, ['id' => 'title', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.accounts.bank_name')]) }}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 col-sm-12 mb-5">
                        {{ Form::label('account_number', __('messages.accounts.account_number') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('account_number', null, ['id' => 'title', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.accounts.account_number')]) }}
                    </div>
                    <div class="form-group col-lg-6 col-sm-12 mb-5 ">
                        {{ Form::label('balance', __('messages.accounts.balance') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::number('balance', null, ['id' => 'title', 'class' => 'form-control form-control-solid', 'required', 'placeholder' => __('messages.accounts.balance')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('address', __('messages.accounts.address') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::textarea('address', null, ['id' => 'payment_note', 'class' => 'form-control form-control-solid', 'rows' => '5']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.accounts.add_account'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'btnPay', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
