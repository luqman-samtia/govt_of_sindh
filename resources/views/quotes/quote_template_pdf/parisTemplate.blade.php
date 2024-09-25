<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.quote.quote_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        /* @page {
            margin-top: 40px !important;
        } */


        @if (getCurrencySymbol($quote->tenant_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body>
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview paris-template">
        <div class="d" id="boxes">
            <div class="d-inner ">
                <div class="" style="margin-top:-40px;">
                    <table class="heading-table w-100">
                        <tr>
                            <td class="pb-10 ">
                                <img width="100px" src="{{ getLogoUrl($quote->tenant_id) }}" class="img-logo">
                            </td>
                            <td class="heading-text">
                                <div class="text-end">
                                    <h1 class="m-0 text-white"
                                        {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                        {{ __('messages.quote.quote_name') }}</h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="top-border"></div>
                    <div class="px-sm-10 px-2  mt-5">
                        <table class="mb-5 w-100">
                            <tbody>
                                <tr style="vertical-align:top;">
                                    <td width="43%;">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.to') . ':' }}</strong>
                                        </p>
                                        <p class="mb-1 font-color-gray fs-6"><b>{{ __('messages.common.name') . ':' }}
                                            </b><span class="font-gray-900">{{ $client->user->full_name }}</span></p>
                                        <p class=" mb-1 font-color-gray fs-6">
                                            <b>{{ __('messages.common.email') . ':' }}</b>
                                            <span class="font-gray-900">{{ $client->user->email }}</span>
                                        </p>
                                        <p class="mb-1 font-color-gray fs-6">
                                            <b>{{ __('messages.common.address') . ':' }}</b>
                                            <span class="font-gray-900">{{ $client->address }} </span>
                                        </p>
                                        @if (!empty($client->vat_no))
                                            <p class="mb-1 font-color-gray fs-6">
                                                <b>{{ getVatNoLabel() . ':' }}</b>
                                                <span class="font-gray-900">{{ $client->vat_no }} </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td width="23.40%;">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.from') . ':' }}</strong>
                                        </p>

                                    </td>
                                    <td width="33.33%;" class="text-end pt-7">
                                        <p class="mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.quote.quote_date') . ':' }}
                                            </strong>{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                        <p class=" mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.quote.due_date') . ':' }}&nbsp;
                                            </strong>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="overflow-auto w-100">
                            <table class="invoice-table w-100">
                                <thead {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                    <tr>
                                        <th class="p-2 text-uppercase" style="width:5% !important;">#</th>
                                        <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                                        <th class="p-2 text-center text-uppercase" style="width:9% !important;">
                                            {{ __('messages.invoice.qty') }}
                                        </th>
                                        <th class="p-2 text-center text-nowrap text-uppercase"
                                            style="width:15% !important;">
                                            {{ __('messages.product.unit_price') }}</th>
                                        <th class="p-2 text-end text-nowrap text-uppercase"
                                            style="width:14% !important;">
                                            {{ __('messages.invoice.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($quote) && !empty($quote))
                                        @foreach ($quote->quoteItems as $key => $quoteItems)
                                            <tr>
                                                <td class="p-2" style="width:5%;"><span>{{ $key + 1 }}</span>
                                                </td>
                                                <td class="p-2 in-w-2">
                                                    <p class="fw-bold mb-0">
                                                        {{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                                    </p>
                                                    @if (
                                                        !empty($quoteItems->product->description) &&
                                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                        <span
                                                            style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ $quoteItems->quantity }}</td>
                                                <td class="p-2 text-center text-nowrap">
                                                    {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                                </td>
                                                <td class="p-2 text-end text-nowrap">
                                                    {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5">
                            <table style="width:250px; margin-left:auto;">
                                <tbody>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
                                            {{ getCurrencyAmount($quote->amount, true) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.quote.discount') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
                                            @if ($quote->discount == 0)
                                                <span>{{ __('messages.common.n/a') }}</span>
                                            @else
                                                @if (isset($quote) && $quote->discount_type == \App\Models\Quote::FIXED)
                                                    <b
                                                        class="euroCurrency">{{ isset($quote->discount) ? getCurrencyAmount($quote->discount, true) : __('messages.common.n/a') }}</b>
                                                @else
                                                    {{ $quote->discount }}<span
                                                        {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="total-amount text-nowrap"
                                    {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                    <tr>
                                        <td class="p-2">
                                            <strong>{{ __('messages.quote.total') . ':' }}</strong>
                                        </td>
                                        <td class="text-end p-2 text-nowrap">
                                            <strong>{{ getCurrencyAmount($quote->final_amount, true) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-3 mt-sm-0 mt-2" style="min-height:100px">
                            <h6 class="font-gray-900 mb5"><b>{{ __('messages.client.notes') . ':' }}</b></h6>
                            <p class="font-gray-600">{!! nl2br($quote->note ?? __('messages.common.n/a')) !!}
                            </p>
                        </div>
                        <div class="mb-4" style="min-height:100px">
                            <h6 class="font-gray-900 mb5"><b>{{ __('messages.invoice.terms') . ':' }}</b>
                            </h6>
                            <p class="font-gray-600 mb-0">{!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</p>
                        </div>
                        <div class="" style="z-index:2 !important; position: relative;">
                            <table class="w-100">
                                <tr>
                                    <td>
                                        <table class="qr-code-table w-100">
                                            <tr>
                                                <td class="p-0">
                                                    <div class="qr-code"
                                                        {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                                        @if (!empty($invoice->paymentQrCode))
                                                            <img class="mt-4 mx-auto" src="<?php echo asset('images/qrcode.png'); ?>"
                                                                height="100" width="100">
                                                        @endif
                                                        <div class="after-content"
                                                            {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align:top;" class="text-end">
                                                    <div class="">
                                                        <h5 class="font-dark-gray mb5 pt-3">
                                                            <b>{{ __('messages.setting.regards') . ':' }}</b>
                                                        </h5>
                                                        <p class="fs-6 text-green"
                                                            {{ $styleCss }}="color:{{ $quote_template_color }}">
                                                            {{ getAppName($quote->tenant_id) }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="bottom-border"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
