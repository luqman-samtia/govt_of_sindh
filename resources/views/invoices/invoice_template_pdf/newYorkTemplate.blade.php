<!DOCTYPE html>
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

        @if (getInvoiceCurrencySymbol($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body style="padding: 25px 15px !important;">
    @php $styleCss = 'style'; @endphp
    <div class="container invoice">
        <div class="invoice-header">
            <table width="100%">
                <tr>
                    <td style="vertical-align:top !important;">
                        <div class="companylogo"><img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}"
                                alt="" class="object-contain"></div>
                    </td>
                    <td>
                        <div class="invoice-header-inner">
                            <h3 {{ $styleCss }}="color: {{ $invoice_template_color }}">
                                <b>{{ __('messages.common.invoice') }}</b>
                            </h3>
                            <span class="text-color">#{{ $invoice->invoice_id }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="details-section">
            <table class="mt-10 w-100">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="invoice-date" style="vertical-align:top !important; width:33.33% !important;">
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.invoice.invoice_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.invoice.due_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                        </td>
                        <td class="billedto"
                            style="vertical-align:top !important; width:33.33% !important; overflow:hidden; word-wrap: break-word; word-break: break-all;">
                            <b>{{ __('messages.common.to') . ':' }}</b><br>
                            <span><b>{{ __('messages.common.name') . ':' }}</b></span> <span
                                class="text-color">{{ $client->user->full_name }}</span><br>
                            <span><b>{{ __('messages.common.email') . ':' }}</b></span>
                            <span class="text-color">{{ $client->user->email }}</span><br>
                            @if (!empty($client->address))
                                <span><b>{{ __('messages.common.address') . ':' }}</b></span>
                                <span class="text-color">{{ $client->address }}</span><br>
                            @endif
                            @if (!empty($client->vat_no))
                                <span><b>{{ getVatNoLabel() . ':' }}</b></span>
                                <span class="text-color">{{ $client->vat_no }}</span><br>
                            @endif
                        </td>
                        <td class="from" style="vertical-align:top !important; width:33.33% !important;">
                            <b>{{ __('messages.common.from') . ':' }}</b><br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b><span
                                class="text-break text-color">{!! $setting['company_address'] !!}</span><br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <div class="text-color">
                                    {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                </div>
                            @endif
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b><span
                                class="text-color">{{ $setting['company_phone'] }}</span><br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <b>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</b><span
                                    class="text-color">{{ $setting['fax_no'] }}</span><br>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="content">
            <table class="table product-table w-100"
                {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                <thead class="bg-light"
                    {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                    <tr>
                        <th style="width:5%;">#</th>
                        <th class="text-uppercase">{{ __('messages.product.product') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:14%;">
                            {{ __('messages.invoice.qty') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:14%;">
                            {{ __('messages.product.unit_price') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:12%;">
                            {{ __('messages.invoice.tax') . '(in %)' }}</th>
                        <th class="text-end text-uppercase text-nowrap" style="width:12%;">
                            {{ __('messages.invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if (isset($invoice) && !empty($invoice))
                        @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                            <tr class="">
                                <td class="" style="width:5%;">{{ $key + 1 }}</td>
                                <td class="">
                                    <p style="margin:0;">
                                        <b>{{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}</b>
                                    </p>
                                    <p style="margin:0;" class="text-color">
                                        @if (
                                            !empty($invoiceItems->product->description) &&
                                                (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                            <span
                                                style="font-size: 12px; word-break: break-all">{{ $invoiceItems->product->description }}</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center text-color" style="width:14%;">
                                    {{ number_format($invoiceItems->quantity, 2) }}
                                </td>
                                <td class="text-center text-color text-nowrap euroCurrency" style="width:14%;">
                                    {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                </td>
                                <td class="text-center text-color text-nowrap" style="width:12%;">
                                    @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                        {{ $tax->tax ?? '--' }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-end text-color euroCurrency text-nowrap" style="width:12%;">
                                    {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <table class="w-100 mt-4">
            <tr>
                <td class="w-65" style="vertical-align:bottom !important;">
                    @if (!empty($invoice->paymentQrCode))
                        <div style="">
                            <strong
                                style="font-size: ; margin-right: 142px"><b>{{ __('messages.payment_qr_codes.payment_qr_code') }}</b></strong><br>
                            <img class="mt-2 ml-3" src="{{ $invoice->paymentQrCode->qr_image }}" height="110"
                                width="110">
                        </div>
                    @endif
                </td>
                <td class="text-end" style="width:35%;">
                    <table class="total-table table w-100">
                        <tbody class="">
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color euroCurrency">
                                    {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}</td>
                            </tr>
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color">
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
                            <tr class="border-bottom-gray">
                                @php
                                    $itemTaxesAmount = $invoice->amount + array_sum($totalTax);

                                    $invoiceTaxesAmount = ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) / 100;
                                    $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                                @endphp
                                <td class="left">
                                    <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                </td>

                                <td class="text-end text-color">
                                    {!! numberFormat($totalTaxes) != 0
                                        ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                        : __('messages.common.n/a') !!}
                                </td>
                            </tr>

                            <tr class="border-bottom-gray">
                                <td class="font-weight-bold">{{ __('messages.invoice.total') . ':' }}</td>
                                <td class="text-nowrap text-end text-color">
                                    {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                </td>
                            </tr>

                            <tr
                                {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                                <td class="font-weight-bold">{{ __('messages.admin_dashboard.total_due') . ':' }}</td>
                                <td class="text-nowrap text-end text-color">
                                    {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}
                                </td>
                            </tr>
                            <tr
                                {{ $styleCss }}="border-top: 1px solid {{ $invoice_template_color }}
                ;border-bottom: 1px solid {{ $invoice_template_color }}">
                                <td class="font-weight-bold pr-3">
                                    {{ __('messages.admin_dashboard.total_paid') . ':' }}
                                </td>
                                <td class="text-nowrap text-end text-color">
                                    {{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="notes-terms">
            <p><b>{{ __('messages.client.notes') . ':' }}</b><br><span class="text-color">
                    {!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}</span></p>
            <p><b>{{ __('messages.invoice.terms') . ':' }}</b><br>
                <span class="text-color">
                    {!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}</span>
            </p>
        </div>
        <div class="regards">
            <p><b>{{ __('messages.setting.regards') . ':' }}</b><br>
                <b
                    {{ $styleCss }}="color: {{ $invoice_template_color }}">{{ html_entity_decode($setting['app_name']) }}</b>
            </p>
        </div>
    </div>
</body>

</html>
