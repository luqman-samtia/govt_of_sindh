<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        @page {
            margin-top: 40px !important;
        }

        @if (getInvoiceCurrencySymbol($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body>
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview hongkong-template">
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="">
                    <div class="invoice-header" style="margin-left:-40px;">
                        <table class="overflow-hidden w-100">
                            <tr>
                                <td class="heading-text" width="26%;">
                                    <div class="text-end">
                                        <h1 class="m-0"
                                            {{ $styleCss }}="background-color: {{ $invoice_template_color }};  font-size: 30px; font-weight:700; letter-spacing:1px;">
                                            {{ __('messages.common.invoice') }}</h1>
                                    </div>
                                    <div class="h1-after"></div>
                                </td>
                                <td class="text-end pr-5">
                                    <div class="">
                                        <img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}"
                                            class="img-logo">
                                    </div>
                                    <div>
                                        <p class="mb-1 font-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.invoice_date') . ':' }}
                                            </strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                        <p class=" mb-1 font-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.due_date') . ':' }}&nbsp;
                                            </strong>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="px-3">
                    <table class="mt-4 mb-3 w-100">
                        <tbody>
                            <tr style="vertical-align:top;">
                                <td width="48%;">
                                    <p class="fs-6 mb-2 font-gray-900">
                                        <b>{{ __('messages.common.to') . ':' }}</b>
                                    </p>
                                    <p class="mb-1 font-gray-600 fs-6"><b>{{ __('messages.common.name') . ':' }}
                                        </b>{{ $client->user->full_name }}</p>
                                    <p class="mb-1 font-gray-600 fs-6"><b>{{ __('messages.common.email') . ':' }}
                                        </b> {{ $client->user->email }}</p>
                                    <p class="mb-1  font-gray-600 fs-6">
                                        <b>{{ __('messages.common.address') . ':' }}
                                        </b>{{ $client->address }}
                                    </p>
                                    @if (!empty($client->vat_no))
                                        <p class="mb-1 font-gray-600 fs-6">
                                            <b>{{ getVatNoLabel() . ':' }}
                                            </b>{{ $client->vat_no }}
                                        </p>
                                    @endif
                                </td>
                                <td width="50%;">
                                    <p class="fs-6 mb-2 font-gray-900">
                                        <b>{{ __('messages.common.from') . ':' }}</b>
                                    </p>
                                    <p class=" mb-1 font-gray-600 fs-6">
                                        <b>{{ __('messages.common.address') . ':' }}&nbsp; </b>
                                        {!! $setting['company_address'] !!}
                                    </p>
                                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                        <p class="m-0 font-gray-600 fs-6">
                                            {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                        </p>
                                    @endif
                                    <p class="mb-1 font-gray-600 fs-6">
                                        <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b><span
                                            class="">{{ $setting['company_phone'] }}</span>
                                    </p>
                                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                        <p class="mb-1 font-gray-600 fs-6">
                                            <b>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</b><span
                                                class="">{{ $setting['fax_no'] }}</span>
                                        <p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-3">
                    <table class="invoice-table w-100">
                        <thead {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                            <tr>
                                <th class="p-2 text-uppercase" style="width:5% !important;">#</th>
                                <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                                <th class="p-2 text-center text-uppercase" style="width:9% !important;">
                                    {{ __('messages.invoice.qty') }}
                                </th>
                                <th class="p-2 text-center text-nowrap text-uppercase" style="width:15% !important;">
                                    {{ __('messages.product.unit_price') }}</th>
                                <th class="p-2 text-center text-nowrap text-uppercase" style="width:13% !important;">
                                    {{ __('messages.invoice.tax') . '(in %)' }}
                                </th>
                                <th class="p-2 text-end text-nowrap text-uppercase" style="width:14% !important;">
                                    {{ __('messages.invoice.amount') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($invoice) && !empty($invoice))
                                @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                    <tr>
                                        <td class="p-2" style="width:5%;"><span>{{ $key + 1 }}</span></td>
                                        <td class="p-2 in-w-2">
                                            <p class="fw-bold mb-0">
                                                {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                            </p>
                                            @if (
                                                !empty($invoiceItems->product->description) &&
                                                    (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                <span
                                                    style="font-size: 12px; word-break: break-all !important">{{ $invoiceItems->product->description }}</span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-center text-nowrap">
                                            {{ number_format($invoiceItems->quantity, 2) }}
                                        </td>
                                        <td class="p-2 text-center text-nowrap">
                                            {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                        </td>
                                        <td class="p-2 text-center text-nowrap">
                                            @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                                {{ $tax->tax ?? '--' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="p-2 text-end text-nowrap">
                                            {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="my-10">
                        <table class=" w-100">
                            <tr>
                                <td>
                                    @if (!empty($invoice->paymentQrCode))
                                        <img class="mt-4" src="{{ $invoice->paymentQrCode->qr_image }}"
                                            height="100" width="100">
                                    @endif
                                </td>
                                <td class="w-50" style="vertical-align:top;">
                                    <table class="w-100 mt-4">
                                        <tbody>
                                            <tr>
                                                <td class="text-nowrap py-1 px-2 text-yellow"
                                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                                    <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end font-gray-600 py-1 px-2">
                                                    {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap py-1 px-2 text-yellow"
                                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                                    <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end font-gray-600 py-1 px-2">
                                                    @if ($invoice->discount == 0)
                                                        <span>{{ __('messages.common.n/a') }}</span>
                                                    @else
                                                        @if (isset($invoice) && $invoice->discount_type == \App\Models\Invoice::FIXED)
                                                            <b
                                                                class="euroCurrency">{{ isset($invoice->discount) ? getInvoiceCurrencyAmount($invoice->discount, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                                        @else
                                                            {{ $invoice->discount }}<span
                                                                {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                @php
                                                    $itemTaxesAmount = $invoice->amount + array_sum($totalTax);
                                                    $invoiceTaxesAmount = ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) / 100;
                                                    $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                                                @endphp
                                                <td class="text-nowrap py-1 px-2 text-yellow"
                                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                                    <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end font-gray-600 py-1 px-2">
                                                    {!! numberFormat($totalTaxes) != 0
                                                        ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                        : __('messages.common.n/a') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap py-1 px-2 text-yellow"
                                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                                    <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end font-gray-600 py-1 px-2">
                                                    {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="border-top-gray">
                                            <tr>
                                                <td class="text-nowrap p-2 text-yellow"
                                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                                    <strong>
                                                        {{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                                </td>
                                                <td class="text-nowrap text-end font-gray-900 p-2">
                                                    {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-5">
                        <div class="mb-3">
                            <h4 class="font-gray-900 mb5">{{ __('messages.client.notes') . ':' }}</h4>
                            <p class="font-gray-600"><span class="me-1"> <svg width="10" height="10"
                                        viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M2 0C0.895431 0 0 0.89543 0 2V8C0 9.10457 0.89543 10 2 10H8C9.10457 10 10 9.10457 10 8V2C10 0.895431 9.10457 0 8 0H2ZM4.72221 2.95508C4.72221 2.7825 4.58145 2.64014 4.41071 2.66555C3.33092 2.82592 2.5 3.80797 2.5 4.99549V7.01758C2.5 7.19016 2.63992 7.33008 2.8125 7.33008H4.40971C4.58229 7.33008 4.72221 7.19016 4.72221 7.01758V5.6021C4.72221 5.42952 4.58229 5.2896 4.40971 5.2896H3.61115V4.95345C3.61115 4.41687 3.95035 3.96422 4.41422 3.82285C4.57924 3.77249 4.72221 3.63715 4.72221 3.4645V2.95508ZM7.5 2.95508C7.5 2.7825 7.35924 2.64014 7.18849 2.66555C6.1087 2.82592 5.27779 3.80797 5.27779 4.99549V7.01758C5.27779 7.19016 5.41771 7.33008 5.59029 7.33008H7.1875C7.36008 7.33008 7.5 7.19016 7.5 7.01758V5.6021C7.5 5.42952 7.36008 5.2896 7.1875 5.2896H6.38885V4.95345C6.38885 4.41695 6.72813 3.96422 7.19193 3.82285C7.35703 3.77249 7.5 3.63715 7.5 3.4645V2.95508Z"
                                            fill="#8B919E" />
                                    </svg></span>{!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
                            </p>
                        </div>
                        <table class="w-100">
                            <tr>
                                <td class="w-50">
                                    <div class="mb-8">
                                        <h4 class="font-gray-900 mb5">{{ __('messages.invoice.terms') . ':' }}</h4>
                                        <p class="font-gray-600">{!! nl2br($invoice->term ?? __('messages.common.not_available')) !!} </p>
                                    </div>
                                </td>
                                <td class="w-25 text-end">
                                    <div class="">
                                        <h5 class="text-yellow mb5"
                                            {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                            {{ __('messages.setting.regards') . ':' }}</h5>
                                        <p class="fs-6"><b>{{ html_entity_decode($setting['app_name']) }}</b></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
