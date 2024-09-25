<div class="row">
    <div class="col-lg-4 col-sm-12 mb-5">
        {{ Form::label('client_id', __('messages.invoice.client') . ':', ['class' => 'form-label required mb-3']) }}
        {{ Form::select('client_id', $clients, $client_id ?? null, ['class' => 'form-select io-select2', 'id' => 'client_id', 'placeholder' => __('messages.invoice.client'), 'required', 'data-control' => 'select2']) }}
    </div>
    <div class="col-lg-4 col-sm-12 mb-lg-0 mb-5">
        @if (!empty(getSettingValue('invoice_no_prefix')) || !empty(getSettingValue('invoice_no_suffix')))
            <div class="" data-bs-toggle="tooltip" data-bs-trigger="hover" title=""
                data-bs-original-title="invoice number">
                <div class="form-group col-sm-12 mb-5">
                    {{ Form::label('paid_amount', __('messages.invoice.invoice_number') . ':', ['class' => 'form-label mb-3 required']) }}
                    <div class="input-group">
                        @if (!empty(getSettingValue('invoice_no_prefix')))
                            <a class="input-group-text bg-secondary border-0 text-decoration-none text-black"
                                data-toggle="tooltip" data-placement="right" title="Invoice No Prefix">
                                {{ getSettingValue('invoice_no_prefix') }}
                            </a>
                        @endif
                        {{ Form::text('invoice_id', \App\Models\Invoice::generateUniqueInvoiceId(), ['class' => 'form-control', 'required', 'id' => 'invoiceId', 'maxlength' => 6, 'onkeypress' => 'return blockSpecialChar(event)']) }}
                        @if (!empty(getSettingValue('invoice_no_suffix')))
                            <a class="input-group-text bg-secondary border-0 text-decoration-none text-black"
                                data-toggle="tooltip" data-placement="right" title="Invoice No Suffix">
                                {{ getSettingValue('invoice_no_suffix') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="" data-bs-toggle="tooltip" data-bs-trigger="hover" title=""
                data-bs-original-title="{{ __('messages.invoice.invoice_number') }}">
                <span class="form-label">{{ __('messages.invoice.invoice') }} #</span>
                {{ Form::text('invoice_id', \App\Models\Invoice::generateUniqueInvoiceId(), ['class' => 'form-control mt-3', 'required', 'id' => 'invoiceId', 'maxlength' => 6, 'onkeypress' => 'return blockSpecialChar(event)']) }}
            </div>
        @endif
    </div>
    <div class="col-lg-4 col-sm-12 mb-5">
        {{ Form::label('invoice_date', __('messages.invoice.invoice_date') . ':', ['class' => 'form-label required mb-3']) }}
        {{ Form::text('invoice_date', null, ['class' => 'form-select', 'id' => 'invoice_date', 'autocomplete' => 'off', 'required']) }}
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('due_date', __('messages.invoice.due_date') . ':', ['class' => 'form-label required mb-3']) }}
        {{ Form::text('due_date', null, ['class' => 'form-select', 'id' => 'due_date', 'autocomplete' => 'off', 'required']) }}
    </div>
    <div class="col-lg-4 col-sm-12">
        <div class="mb-5">
            {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::select('status', $statusArr, null, ['class' => 'form-select io-select2', 'id' => 'status', 'required', 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('templateId', __('messages.setting.invoice_template') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::select('template_id', $template, getInvoiceTemplateId() ?? null, ['class' => 'form-select io-select2', 'id' => 'templateId', 'required', 'data-control' => 'select2']) }}
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('payment_qr_code_id', __('messages.payment_qr_codes.payment_qr_code') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::select('payment_qr_code_id', $paymentQrCodes, $defaultPaymentQRCode ?? null, ['class' => 'form-select io-select2 payment-qr-code', 'data-control' => 'select2', 'placeholder' => 'Select Payment QR Code']) }}
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('invoiceCurrencyType', __('messages.setting.currencies') . ':', ['class' => 'form-label mb-3']) }}
        <select id="invoiceCurrencyType" class="form-select" name="currency_id">
            <option value="">{{ __('messages.invoice.select_currency') }}</option>
            @foreach ($currencies as $key => $currency)
                <option value="{{ $currency['id'] }}">{{ $currency['icon'] }}
                    &nbsp;&nbsp;&nbsp; {{ $currency['name'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-5 col-lg-4 col-sm-12 mt-8">
        <label class="form-check form-switch form-check-custom mt-3">
            <input class="form-check-input recurring-status" type="checkbox" name="recurring_status"
                id="recurringStatusToggle">
            <span class="form-check-label text-gray-600"
                for="recurringStatusToggle">{{ __('messages.invoice.this_is_recurring_invoice') }}</span>&nbsp;&nbsp;
        </label>
    </div>
    <div class="mb-5 col-lg-4 col-sm-12 recurring-cycle-content">
        {{ Form::label('recurringCycle', __('messages.invoice.recurring_cycle') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::number('recurring_cycle', null, ['class' => 'form-control', 'id' => 'recurringCycle', 'autocomplete' => 'off', 'placeholder' => __('messages.invoice.recurring_days'), 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) }}
    </div>
    <div class="col-12 text-end my-5">
        <button type="button" class="btn btn-primary text-start" id="addItem">
            {{ __('messages.invoice.add') }}</button>
    </div>
    <div class="table-responsive">
        <table class="table table-striped box-shadow-none mt-4" id="billTbl">
            <thead>
                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                    <th scope="col">#</th>
                    <th scope="col" class="required">{{ __('messages.product.product') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.qty') }}</th>
                    <th scope="col" class="required">{{ __('messages.product.unit_price') }}</th>
                    <th scope="col">{{ __('messages.invoice.tax') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.amount') }}</th>
                    <th scope="col" class="text-end">{{ __('messages.common.action') }}</th>
                </tr>
            </thead>
            <tbody class="invoice-item-container">
                <tr class="tax-tr">
                    <td class="text-center item-number align-center">1</td>
                    <td class="table__item-desc w-25">
                        {{ Form::select('product_id[]', $products, null, ['class' => 'form-select product io-select2', 'required', 'placeholder' => __('messages.quote.select_product'), 'data-control' => 'select2']) }}
                    </td>
                    <td class="table__qty">
                        {{ Form::number('quantity[]', null, ['class' => 'form-control qty form-control-solid', 'required', 'type' => 'number', 'min' => '0', 'step' => '.01', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) }}
                    </td>
                    <td>
                        {{ Form::number('price[]', null, ['class' => 'form-control price-input price form-control-solid', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
                    </td>
                    <td>
                        <select name="tax[]" class='form-select io-select2 fw-bold tax' data-control='select2'
                            multiple="multiple">
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->value }}" data-id="{{ $tax->id }}"
                                    {{ $defaultTax == $tax->id ? 'selected' : '' }}>{{ $tax->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-end item-total pt-8 text-nowrap">
                        @if (!getSettingValue('currency_after_amount'))
                            <span>{{ getCurrencySymbol() }}</span>
                        @endif
                        0.00 @if (getSettingValue('currency_after_amount'))
                            <span>{{ getCurrencySymbol() }}</span>
                        @endif
                    </td>
                    <td class="text-end">

                        <button type="button" title="Delete"
                            class="btn btn-icon fs-3 text-danger btn-active-color-danger delete-invoice-item">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-7 col-sm-12 mt-2 mt-lg-0 align-right-for-full-screen">
            <div class="mb-2 col-xl-5 col-lg-8 col-sm-12 float-right">
                {{ Form::label('discount', __('messages.invoice.discount') . ':', ['class' => 'form-label mb-1']) }}
                <div class="input-group">
                    {{ Form::number('discount', 0, ['id' => 'discount', 'class' => 'form-control ', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$"]) }}
                    <div class="input-group-append" style="width: 210px !important;">
                        {{ Form::select('discount_type', $discount_type, 0, ['class' => 'form-select io-select2 discount-type', 'id' => 'discountType', 'data-control' => 'select2']) }}
                    </div>
                </div>
            </div>
            <div class="mb-2 col-xl-5 col-lg-8 col-sm-12 float-right">
                {{ Form::label('tax2', __('messages.invoice.tax') . ':', ['class' => 'form-label mb-1']) }}
                <select name="taxes[]" class='form-select io-select2 fw-bold invoice-taxes' data-control='select2'
                    multiple="multiple">
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}" data-tax="{{ $tax->value }}">{{ $tax->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-5 col-md-6 ms-md-auto mt-4 mb-lg-10 mb-6">
            <div class="border-top">
                <table class="table table-borderless box-shadow-none mb-0 mt-5">
                    <tbody>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.sub_total') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                @if (!getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif <span id="total" class="price">
                                    0
                                </span>
                                @if (getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.discount') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                @if (!getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif <span id="discountAmount">
                                    0
                                </span>
                                @if (getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.total_tax') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                @if (!getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif <span id="totalTax">
                                    0
                                </span>
                                @if (getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.total') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                @if (!getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif <span id="finalAmount">
                                    0
                                </span>
                                @if (getSettingValue('currency_after_amount'))
                                    <span>{{ getCurrencySymbol() }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-12 col-md-12 col-sm-12 end justify-content-left mb-5">
            <button type="button" class="btn btn-primary note" id="addNote"><i class="fas fa-plus"></i>
                {{ __('messages.invoice.add_note_term') }}</button>
            <button type="button" class="btn btn-danger note" id="removeNote"><i class="fas fa-minus"></i>
                {{ __('messages.invoice.remove_note_term') }}</button>
        </div>
        <div class="col-lg-6 mb-5 mt-5" id="noteAdd">
            {{ Form::label('note', __('messages.invoice.note') . ':', ['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3', 'rows' => '5']) }}
            {{ Form::textarea('note', null, ['class' => 'form-control', 'id' => 'note']) }}
        </div>
        <div class="col-lg-6 mb-5 mt-5" id="termRemove">
            {{ Form::label('term', __('messages.invoice.terms') . ':', ['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3', 'rows' => '5']) }}
            {{ Form::textarea('term', null, ['class' => 'form-control', 'id' => 'term']) }}
        </div>
    </div>
</div>

<!-- Total Amount Field -->
{{ Form::hidden('amount', 0, ['class' => 'form-control', 'id' => 'total_amount']) }}

<!-- Final Amount Field -->
{{ Form::hidden('final_amount', 0, ['class' => 'form-control', 'id' => 'finalTotalAmt']) }}

<!-- Submit Field -->
<div class="float-end">
    <div class="form-group col-sm-12">
        <button type="button" name="draft" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0" id="saveAsDraft"
            data-status="0" value="0">{{ __('messages.common.save_draft') }}
        </button>
        <button type="button" name="save" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0" id="saveAndSend"
            data-status="1" value="1">{{ __('messages.common.save_send') }}
        </button>
        <a href="{{ route('invoices.index') }}"
            class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
