<div class="row">
    <div class="col-lg-4 col-sm-12 mb-5">
        {{ Form::label('client_id', __('messages.quote.client').(':'),['class' => 'form-label required fs-6 text-gray-700 mb-3']) }}
        {{ Form::text('client', getLogInUser()->full_name, ['class' => 'form-control', 'readonly']) }}
        {{ Form::hidden('client_id', getLogInUserId(), ['class' => 'form-control', 'id' => 'client_id']) }}
    </div>
    <div class="col-lg-4 col-sm-12 mt-1 mb-5">
        <h4 class="align-items-center">{{__('messages.quote.quote')}} # <span
                    class="text-gray-500">{{ $quote->quote_id }}</span></h4>
        <input type="hidden" id="quoteId" value="{{ $quote->quote_id }}" name="quote_id"/>
    </div>
    <div class="col-lg-4 col-sm-12">
        <div class="mb-5">
            {{ Form::label('status', __('messages.common.status').(':'), ['class' => 'form-label required mb-3']) }}
            {{ Form::select('status', $statusArr, isset($quote) ? $quote->status : null, ['class' => 'form-select', 'id' => 'status','required','data-control' =>'select2']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-5">
        {{ Form::label('quote_date', __('messages.quote.quote_date').(':'),['class' => 'form-label required mb-3']) }}
        {{ Form::text('quote_date',null ,['class' => 'form-select', 'id' => 'clientEditQuoteDate', 'autocomplete' => 'off','required']) }}
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('due_date', __('messages.quote.due_date').(':'),['class' => 'form-label required mb-3']) }}
        {{ Form::text('due_date', null, ['class' => 'form-select', 'id' => 'clientEditQuoteDueDate', 'autocomplete' => 'off','required']) }}
    </div>
    <div class="mb-5 col-lg-4 col-sm-12">
        {{ Form::label('templateId', __('messages.setting.invoice_template').(':'),['class' => 'form-label mb-3']) }}
        {{ Form::select('template_id', $template,isset($quote) ? $quote->template_id:null, ['class' => 'form-select', 'id' => 'templateId','required', 'data-control' =>'select2']) }}
    </div>
    <div class="mb-0">
        <div class="col-12 text-end my-5">
            <button type="button" class="btn btn-primary text-start"
                    id="addClientQuoteItem"> {{ __('messages.invoice.add') }}</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped box-shadow-none mt-4" id="billTbl">
                <thead>
                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                    <th scope="col">#</th>
                    <th scope="col" class="required">{{ __('messages.product.product') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.qty') }}</th>
                    <th scope="col" class="required">{{ __('messages.product.unit_price') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.amount') }}</th>
                    <th scope="col" class="text-end">{{ __('messages.common.action') }}</th>
                </tr>
                </thead>
                <tbody class="quote-item-container">
                @php
                    $i = 1;
                @endphp
                @foreach($quote->quoteItems as $quoteItem)
                    <tr class="tax-tr">
                        <td class="text-center item-number align-center">{{ $i++ }}</td>
                        <td class="table__item-desc w-25">
                            {{ Form::select('product_id[]', $products, isset($quoteItem->product_id)?$quoteItem->product_id:$quoteItem->product_name??[], ['class' => 'form-select productId client-product-quote io-select2', 'required', 'placeholder'=>'Select Product or Enter free text', 'data-control' => 'select2']) }}
                            {{ Form::hidden('id[]', $quoteItem->id) }}
                        </td>
                        <td class="table__qty">
                            {{ Form::number('quantity[]', $quoteItem->quantity, ['class' => 'form-control qty-quote' ,'id'=>'qty','required', 'type' => 'number', "min" => 1,'oninput'=>"validity.valid||(value=value.replace(/\D+/g, ''))"]) }}
                        </td>
                        <td>
                            {{ Form::number('price[]', $quoteItem->price, ['class' => 'form-control price-input price-quote','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','step'=>'.01','required','onKeyPress'=>'if(this.value.length==8) return false;']) }}
                        </td>
                        <td class="quote-item-total pt-8 text-nowrap">
                            {{ number_format($quoteItem->total, 2) }}
                        </td>
                        <td class="text-end">
                            <button type="button" title="Delete"
                                    class="btn btn-icon fs-3 text-danger btn-active-color-danger delete-quote-item">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-7 col-sm-12 mt-2 mt-lg-0 align-right-for-full-screen">
                <div class="mb-2 col-xl-6 col-lg-8 col-sm-12 float-right">
                    {{ Form::label('discount', __('messages.invoice.discount').(':'), ['class' => 'form-label mb-1']) }}
                    <div class="input-group">
                        {{ Form::number('discount', isset($quote) ? $quote->discount : 0, ['id'=>'discount','class' => 'form-control ','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','value'=>'0','step'=>'.01','pattern'=>"^\d*(\.\d{0,2})?$"]) }}
                        <div class="input-group-append" style="width: 210px !important;">
                            {{ Form::select('discount_type', $discount_type, isset($quote) ? $quote->discount_type : 0, ['class' =>'form-select', 'id' => 'discountType', 'data-control' =>'select2']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-5 col-md-6 ms-md-auto mt-4 mb-lg-10 mb-6">
                <div class="border-top">
                    <table class="table table-borderless box-shadow-none mb-0 mt-5">
                        <tbody>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.sub_total').(':') }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                <span>{{ getCurrencySymbol()  }}</span> <span id="quoteTotal" class="price">
                                    {{ isset($quote) ? number_format($quote->amount,2) : 0 }}
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.discount').(':') }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                <span>{{ getCurrencySymbol()  }}</span> <span id="quoteDiscountAmount">
                                @if(isset($quote) && $quote->discount_type == \App\Models\Invoice::FIXED)
                                        {{ $quote->discount ?? 0 }}
                                    @else
                                        {{ isset($quote) ? number_format($quote->amount * $quote->discount / 100,2) : 0 }}
                                    @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.total').(':') }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                <span>{{ getCurrencySymbol() }}</span> <span id="quoteFinalAmount">
                                    {{ isset($quote) ? number_format($quote->amount - ($quote->amount * $quote->discount / 100),2) : 0 }}
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-left">
            <div class="ol-lg-12 col-md-12 col-sm-12 end justify-content-left mt-5 mb-5">
                <button type="button" class="btn btn-primary note" id="quoteAddNote">
                    <i class="fas fa-plus"></i> {{ __('messages.invoice.add_note_term') }}
                </button>
                <button type="button" class="btn btn-danger note" id="quoteRemoveNote">
                    <i class="fas fa-minus"></i> {{ __('messages.invoice.remove_note_term') }}
                </button>
            </div>
            <div class="col-lg-6 mt-5 mb-5" id="quoteNoteAdd">
                {{ Form::label('note', __('messages.invoice.note').(':'),['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3']) }}
                {{ Form::textarea('note',isset($quote) ? $quote->note : null,['class'=>'form-control','id'=>'quoteNote','rows' => '5']) }}
            </div>
            <div class="col-lg-6 mt-5 mb-5" id="quoteTermRemove">
                {{ Form::label('term', __('messages.invoice.terms').(':'),['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3']) }}
                {{ Form::textarea('term',isset($quote) ? $quote->term : null,['class'=>'form-control','id'=>'quoteTerm','rows' => '5']) }}
            </div>
        </div>
    </div>
</div>
<!-- Total Amount Field -->
{{ Form::hidden('amount', isset($quote) ? number_format($quote->amount - ($quote->amount * $quote->discount / 100),2) : 0, ['class' => 'form-control', 'id' => 'quoteTotalAmount']) }}

<!-- Submit Field -->
<div class="float-end">
    <div class="form-group col-sm-12">
        <button type="button" name="save" class="btn btn-primary mx-3" id="editSaveClientQuote" data-status="0"
                value="0">{{ __('messages.common.save') }}
        </button>
        <a href="{{ route('client.quotes.index') }}"
           class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
