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
            /* margin-top: 30px !important; */
        }

        @if (getInvoiceCurrencySymbol($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body style="padding: 0rem 1.5rem;">
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview paris-template">
        <div class="d" id="boxes">
            <div class="d-inner ">
                <div class="" style="margin-top:-40px;">
                    <table class="heading-table w-100">
                        <tr>
                            <td class="pb-10 ">
                                <img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}" class="img-logo">
                            </td>
                            <td class="heading-text">
                                <div class="text-end">
                                    <h1 class="m-0 text-white"
                                        {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                        {{ __('messages.common.invoice') }}</h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="top-border"></div>
                    <div class="px-sm-10 px-2 mt-5">
                        <table class="mb-5 w-100">
                            <tbody>
                                <tr style="vertical-align:top;">
                                    <td width="40%;">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.to') . ':' }}</strong>
                                        </p>
                                        <p class=" mb-1 font-gray-600 fs-6">
                                            <strong>{{ __('messages.common.name') . ':' }}</strong> <span
                                                class="font-gray-900">{{ $client->user->full_name }}</span>
                                        </p>
                                        <p class=" mb-1 font-gray-600 fs-6">
                                            <strong>{{ __('messages.common.email') . ':' }}</strong>
                                            <span class="font-gray-900">{{ $client->user->email }}</span>
                                        </p>
                                        <p class=" mb-1 font-gray-600 fs-6">
                                            <strong>{{ __('messages.common.address') . ':' }}</strong>
                                            <span class="font-gray-900">{{ $client->address }} </span>
                                        </p>
                                        @if (!empty($client->vat_no))
                                            <strong>{{ getVatNoLabel() . ':' }}</strong>
                                            <span class="font-gray-900">{{ $client->vat_no }} </span>
                                        @endif
                                    </td>
                                    <td width="30%;">
                                        <p class="fs-6 mb-2 font-gray-900">
                                            <strong>{{ __('messages.common.from') . ':' }}</strong>
                                        </p>
                                        <p class="mb-1 font-gray-600 fs-6"><strong>
                                                {{ __('messages.common.address') . ':' }}</strong>&nbsp; <span
                                                class="font-gray-900">{!! $setting['company_address'] !!}</span></p>
                                        @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                            <p class="m-0 font-gray-900 fs-6">
                                                {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                            </p>
                                        @endif
                                        <p class=" mb-1 font-gray-600 fs-6"><strong>
                                                {{ __('messages.user.phone') . ':' }}</strong>&nbsp; <span
                                                class="font-gray-900">{{ $setting['company_phone'] }}</span></p>
                                        @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                            <p class="mb-1 font-gray-600 fs-6">
                                                <strong>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</strong><span
                                                    class="font-gray-900">{{ $setting['fax_no'] }}</span>
                                            <p>
                                        @endif
                                    </td>
                                    <td width="30%;" class="text-end pt-7">
                                        <p class="mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.invoice_date') . ':' }}
                                            </strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                        <p class=" mb-1 text-gray-600 fs-6"><strong
                                                class="font-gray-900">{{ __('messages.invoice.due_date') . ':' }}&nbsp;
                                            </strong>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="overflow-auto w-100">
                            <table class="invoice-table w-100">
                                <thead {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                    <tr>
                                        <th class="p-2 text-uppercase" style="width:5% !important;">#</th>
                                        <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                                        <th class="p-2 text-center text-uppercase" style="width:9% !important;">
                                            {{ __('messages.invoice.qty') }}
                                        </th>
                                        <th class="p-2 text-center text-nowrap text-uppercase"
                                            style="width:15% !important;">
                                            {{ __('messages.product.unit_price') }}</th>
                                        <th class="p-2 text-center text-nowrap text-uppercase"
                                            style="width:13% !important;">
                                            {{ __('messages.invoice.tax') . '(in %)' }}
                                        </th>
                                        <th class="p-2 text-end text-nowrap text-uppercase"
                                            style="width:14% !important;">
                                            {{ __('messages.invoice.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($invoice) && !empty($invoice))
                                        @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                            <tr>
                                                <td class="p-2" style="width:5%;"><span>{{ $key + 1 }}</span>
                                                </td>
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
                                                <td class="p-2 text-center">
                                                    {{ number_format($invoiceItems->quantity, 2) }}</td>
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
                        </div>
                        <div class="mt-5">
                            <table style="width:250px; margin-left:auto;">
                                <tbody>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
                                            {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
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
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
                                            {!! numberFormat($totalTaxes) != 0
                                                ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                : __('messages.common.n/a') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                        </td>
                                        <td class="text-end font-gray-600 py-1 px-2 text-nowrap">
                                            {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="total-amount text-nowrap"
                                    {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                    <tr>
                                        <td class="p-2">
                                            <strong>{{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                        </td>
                                        <td class="text-end p-2 text-nowrap">
                                            <strong>
                                                {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-3 mt-sm-0 mt-2" style="min-height:80px">
                            <h6 class="font-gray-900 mb5"><b>{{ __('messages.client.notes') . ':' }}</b></h6>
                            <p class="font-gray-600">{!! nl2br($invoice->note ?? __('messages.common.not_available')) !!} </p>
                        </div>
                        <table class="mb-4 w-sm-50 w-100" style="min-height:80px">
                            <tr>
                                <td>
                                    <div class="">
                                        <h6 class="font-gray-900 mb5"><b>{{ __('messages.invoice.terms') . ':' }}</b>
                                        </h6>
                                        <p class="font-gray-600 mb-0">{!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="" style="z-index:2 !important; position: relative;">
                            <table class="w-100">
                                <tr>
                                    <td>
                                        <table class="qr-code-table w-100">
                                            <tr>
                                                <td class="p-0">
                                                    <div class="qr-code"
                                                        {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                                        @if (!empty($invoice->paymentQrCode))
                                                            <img class="mt-4 mx-auto"
                                                                src="{{ $invoice->paymentQrCode->qr_image }}"
                                                                height="100" width="100">
                                                        @endif
                                                        <div class="after-content"
                                                            {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align:top;" class="text-end">
                                                    <div class="">
                                                        <h5 class="font-dark-gray mb5 pt-3">
                                                            <b>{{ __('messages.setting.regards') . ':' }}</b>
                                                        </h5>
                                                        <p class="fs-6 text-green"
                                                            {{ $styleCss }}="color:{{ $invoice_template_color }}">
                                                            {{ html_entity_decode($setting['app_name']) }}
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
