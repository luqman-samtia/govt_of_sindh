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
            margin-bottom: 30px !important;
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
    <div style="width: 100%;" style="margin-top:-40px !important;">

        <table class="mb-8" style="width: 100%;">
            <tr>
                <td style="vertical-align:top; width: 35%;" class="pt-5">
                    <img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}" alt="">
                </td>
                <td style="width: 35%;" class="pt-5">
                    <p class="p-text mb-0">{{ __('messages.invoice.invoice_id') . ':' }}&nbsp;
                        <strong>#{{ $invoice->invoice_id }}</strong>
                    </p>
                    <p class="p-text mb-0">{{ __('messages.invoice.invoice_date') . ':' }}
                        <strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}</strong>
                    </p>
                    <p class="p-text mb-0">{{ __('messages.invoice.due_date') . ':' }}&nbsp;
                        <strong>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}</strong>
                    </p>
                </td>
                <td class="in-w-4 pt-5"
                    {{ $styleCss }}="background-color: {{ $invoice_template_color }}; width: 30%;">
                    <h1 class="fancy-title tu text-center mb-auto p-3" style="color:white;  font-size: 34px;">
                        <b>{{ __('messages.common.invoice') }}</b>
                    </h1>
                </td>
            </tr>
        </table>
        <table style="width:70%;" class="mb-8 mt-5">
            <tr>
                <td class="w-50" style="vertical-align:top !important;">
                    <p class="fs-6 mb-2"><strong>{{ __('messages.common.to') . ':' }}</strong></p>
                    <p class=" m-0 font-color-gray fs-6">{{ __('messages.common.name') . ':' }} <span
                            class="text-dark fw-bold">{{ $client->user->full_name }}</span>
                    </p>
                    <p class=" m-0 font-color-gray fs-6">{{ __('messages.common.email') . ':' }} <span
                            class="text-dark fw-bold">{{ $client->user->email }}</span></p>
                    <p class=" m-0  font-color-gray fs-6">{{ __('messages.common.address') . ':' }} <span
                            class="text-dark fw-bold">{{ $client->address }}</span></p>
                    @if (!empty($client->vat_no))
                        <p class=" m-0  font-color-gray fs-6">{{ getVatNoLabel() . ':' }} <span
                                class="text-dark fw-bold">{{ $client->vat_no }}</span>
                        </p>
                    @endif
                </td>
                <td class="w-50">
                    <p class="fs-6 mb-2"><strong>{{ __('messages.common.from') . ':' }}</strong></p>
                    <p class=" m-0 font-color-gray fs-6">{{ __('messages.common.address') . ':' }}&nbsp;<span
                            class="text-dark fw-bold">{!! $setting['company_address'] !!}</p>
                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                        <p class="m-0 fs-6">
                            {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                        </p>
                    @endif
                    <p class=" m-0 font-color-gray fs-6">{{ __('messages.user.phone') . ':' }}&nbsp;<span
                            class="text-dark fw-bold">{{ $setting['company_phone'] }}</span></p>
                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                        <p class=" m-0 font-color-gray fs-6">{{ __('messages.invoice.fax_no') . ':' }}&nbsp;<span
                                class="text-dark fw-bold">{{ $setting['fax_no'] }}</span>
                        <p>
                    @endif
                </td>
            </tr>
        </table>
        <div class="table-responsive-sm table-striped mt-5" style="width: 100%;">
            <table style="width: 100%;">
                <thead {{ $styleCss }}="background-color: {{ $invoice_template_color }}; ">
                    <tr>
                        <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                            style="width: 7%;">#</th>
                        <th class="px-2 py-1 text-white in-w-2 fw-bold text-uppercase">
                            {{ __('messages.product.product') }}</th>
                        <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                            style="width: 12%;">
                            {{ __('messages.invoice.qty') }}</th>
                        <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                            style="width: 13%;">
                            {{ __('messages.product.unit_price') }}</th>
                        <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                            style="width: 13%;">
                            {{ __('messages.invoice.tax') . '(in %)' }}</th>
                        <th class="px-2 py-1 text-white number-align fw-bold text-uppercase text-nowrap"
                            style="width: 13%;">
                            {{ __('messages.invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($invoice) && !empty($invoice))
                        @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                            <tr class="border-b-gray">
                                <td class="p-2 text-center bg-gray fw-bold">{{ $key + 1 }}</td>
                                <td class="p-2 in-w-2">
                                    <p class="fw-bold mb-0">
                                        {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                    </p>
                                    @if (
                                        !empty($invoiceItems->product->description) &&
                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                        <span
                                            style="font-size: 12px; word-break: break-all">{{ $invoiceItems->product->description }}</span>
                                    @endif
                                </td>
                                <td class="p-2 text-nowrap text-center fw-bold text-nowrap">
                                    {{ number_format($invoiceItems->quantity, 2) }}
                                </td>
                                <td class="p-2 text-nowrap text-nowrap text-center bg-gray fw-bold text-nowrap">
                                    {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                </td>
                                <td class="p-2 text-nowrap text-center fw-bold text-nowrap">
                                    @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                        {{ $tax->tax ?? '--' }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
                                    {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.sub_total') . ':' }}
                        </td>
                        <td class="p-2 text-nowrap text-end bg-gray fw-bold text-nowrap">
                            {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}</td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.discount') . ':' }}
                        </td>
                        <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
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
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @php
                            $itemTaxesAmount = $invoice->amount + array_sum($totalTax);
                            $invoiceTaxesAmount = ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) / 100;
                            $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                        @endphp
                        <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.tax') . ':' }}</td>
                        <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
                            {!! numberFormat($totalTaxes) != 0
                                ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                : __('messages.common.n/a') !!}
                        </td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center fw-bold text-nowrap">
                            {{ __('messages.admin_dashboard.total_due') . ':' }}
                        </td>
                        <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
                            {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}
                        </td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center fw-bold text-nowrap">
                            {{ __('messages.admin_dashboard.total_paid') . ':' }}
                        </td>
                        <td class="p-2 text-nowrap number-align bg-gray fw-bold text-nowrap">
                            {{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) }}
                        </td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center fw-bold text-nowrap">
                            <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                        </td>
                        <td class="p-2 text-nowrap number-align text-white fw-bold text-nowrap"
                            {{ $styleCss }}="background-color: {{ $invoice_template_color }}; ">
                            {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if (!empty($invoice->paymentQrCode))
            <div>
                <p class="m-0 fs-6" {{ $styleCss }}="color: {{ $invoice_template_color }}">
                    <b>{{ __('messages.payment_qr_codes.payment_qr_code') }}</b>
                </p>
                <img class="ml-3" src="{{ $invoice->paymentQrCode->qr_image }}" height="100" width="100">
            </div>
        @endif
        <div class="mt-5">
            <h6 class="d-fancy-title mb5">{{ __('messages.client.notes') . ':' }}</h6>
            <p class="font-color-gray">
                {!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}</p>
        </div>

        <table class="w-100">
            <tr>
                <td class="w-75">
                    <div class="mb-8">
                        <h6 class="d-fancy-title mb5">{{ __('messages.invoice.terms') . ':' }}</h6>
                        <p class="font-color-gray">
                            {!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}</p>
                    </div>

                </td>
                <td class="w-25 number-align">
                    <div class="">
                        <h6 class="d-fancy-title mb5" {{ $styleCss }}="color: {{ $invoice_template_color }}">
                            {{ __('messages.setting.regards') . ':' }}</h6>
                        <p class="font-color-gray">
                            <b>{{ html_entity_decode($setting['app_name']) }}</b>
                        </p>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
