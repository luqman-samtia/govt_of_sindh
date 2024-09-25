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

<body style="padding: 0rem 2rem;">
    @php $styleCss = 'style'; @endphp
    <div>
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="" style="margin-top:-40px !important;">
                    <div class="header-section " {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                        <table class="w-100 pt-5">
                            <tr>
                                <td class="bg-gray-100">
                                    <div class="px-3">
                                        <img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}" alt="">
                                    </div>
                                </td>
                                <td class="bg-black invoice-text" style="width:45%;">
                                    <div class="number-align">
                                        <h1 class="m-0 p-3" style="color:white;  font-size: 34px">
                                            <b>{{ __('messages.common.invoice') }}</b>
                                        </h1>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-white number-align px-3 py-2 fs-6">
                                    <strong>#{{ $invoice->invoice_id }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <table class="mb-8 w-100">
                    <tbody>
                        <tr style="vertical-align:top;">
                            <td width="33.33%;">
                                <p class="fs-6 mb-2"><strong>{{ __('messages.common.from') . ':' }}</strong></p>
                                <p class=" m-0 font-color-gray fw-bold fs-6">
                                    <strong>{{ __('messages.common.address') . ':' }}&nbsp;</strong>
                                    {!! $setting['company_address'] !!}
                                </p>
                                @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                    <p class=" m-0 font-color-gray fs-6">
                                        {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                    </p>
                                @endif
                                <p class=" m-0 font-color-gray fs-6">
                                    <strong>{{ __('messages.user.phone') . ':' }}&nbsp;</strong><span
                                        class="text-color">{{ $setting['company_phone'] }}</span>
                                </p>
                                @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                    <p class=" m-0 font-color-gray fs-6">
                                        <strong>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</strong><span
                                            class="text-color">{{ $setting['fax_no'] }}</span>
                                    <p>
                                @endif
                            </td>
                            <td width="33.33%;" class="ps-sm-5rem">
                                <p class="fs-6 mb-2"><strong>{{ __('messages.common.to') . ':' }}</strong></p>
                                <p class=" m-0 font-color-gray fs-6"><strong>{{ __('messages.common.name') . ':' }}
                                    </strong> {{ $client->user->full_name }}</p>
                                <p class=" m-0 font-color-gray fs-6">
                                    <strong>{{ __('messages.common.email') . ':' }}</strong>
                                    {{ $client->user->email }}
                                </p>
                                <p class=" m-0  font-color-gray fs-6"><strong>{{ __('messages.common.address') . ':' }}
                                    </strong>{{ $client->address }}</p>
                                @if (!empty($client->vat_no))
                                    <p class=" m-0  font-color-gray fs-6">
                                        <strong>{{ getVatNoLabel() . ':' }}
                                        </strong>{{ $client->vat_no }}
                                    </p>
                                @endif
                            </td>
                            <td width="33.33%;" class="number-align">
                                <p class="mb-2 font-color-gray fs-6">
                                    <strong>{{ __('messages.invoice.invoice_date') . ':' }}</strong>
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                                <p class="  font-color-gray fs-6">
                                    <strong>{{ __('messages.invoice.due_date') . ':' }}&nbsp;
                                    </strong>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                                @if (!empty($invoice->paymentQrCode))
                                    <img class="mt-4" src="{{ $invoice->paymentQrCode->qr_image }}" height="100"
                                        width="100">
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="border-b-gray w-100 mt-5">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2" style="width:5% !important;">#</th>
                            <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                            <th class="p-2 text-center text-uppercase" style="width:9% !important;">
                                {{ __('messages.invoice.qty') }}
                            </th>
                            <th class="p-2 text-center text-uppercase text-nowrap" style="width:15% !important;">
                                {{ __('messages.product.unit_price') }}</th>
                            <th class="p-2 text-center text-uppercase text-nowrap" style="width:14% !important;">
                                {{ __('messages.invoice.tax') . '(in %)' }}
                            </th>
                            <th class="p-2 number-align text-uppercase text-nowrap" style="width:14% !important;">
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
                                    <td class="p-2 text-center" style="width:9%;">
                                        {{ number_format($invoiceItems->quantity, 2) }}</td>
                                    <td class="p-2 text-center text-nowrap" style="width:15%;">
                                        {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                    </td>
                                    <td class="p-2 text-center text-nowrap" style="width:14%;">
                                        @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                            {{ $tax->tax ?? '--' }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="p-2 number-align text-nowrap" style="width:14%;">
                                        {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="w-100">
                    <tr>
                        <td class="w-50"></td>
                        <td class="w-50">
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                        </td>
                                        <td class="number-align font-color-gray py-1 px-2 fw-bold text-nowrap">
                                            {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                        </td>
                                        <td class="number-align font-color-gray py-1 px-2 fw-bold text-nowrap">
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
                                        <td class="text-nowrap number-align font-color-gray py-1 px-2 fw-bold">
                                            {!! numberFormat($totalTaxes) != 0
                                                ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                : __('messages.common.n/a') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap fw-bold py-1 px-2">
                                            <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                        </td>
                                        <td class="text-nowrap number-align py-1 px-2 fw-bold">
                                            {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="text-white"
                                    {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                    <tr>
                                        <td class="p-2 text-nowrap">
                                            <strong>{{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                        </td>
                                        <td class="number-align p-2 text-nowrap">
                                            <strong>{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="" style="margin-top:3rem;">
                    <h6 class="d-fancy-title mb5">{{ __('messages.client.notes') . ':' }}</h6>
                    <p class="font-color-gray">{!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
                    </p>
                    <table class="w-100">
                        <tr>
                            <td class="w-75">
                                <div class="mb-8">
                                    <h6 class="d-fancy-title mb5">{{ __('messages.invoice.terms') . ':' }}</h6>
                                    <p class="font-color-gray">{!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}</p>
                                </div>
                            </td>
                            <td class="w-25 number-align">
                                <div class="number-align">
                                    <h6 class="d-fancy-title mb5">{{ __('messages.setting.regards') . ':' }}</h6>
                                    <p class="fw-bold text-purple"
                                        {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                        {{ html_entity_decode($setting['app_name']) }} </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
