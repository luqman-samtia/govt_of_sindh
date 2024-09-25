<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />


</head>

<body style="padding: 30px 15px !important;">
    @php $styleCss = 'style'; @endphp
    <div>
        <div class="">
            <div class="logo"><img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}" alt="no-image">
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered w-100">
                <thead class="bg-light">
                    <tr>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">
                            {{ __('messages.common.from') }}</th>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">{{ __('messages.common.to') }}
                        </th>
                        <th class="py-1 text-uppercase" style="width:33.33% !important;">
                            {{ __('messages.common.invoice') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-1 " style="">
                            {{ html_entity_decode($setting['app_name']) }}<br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b><span
                                class="text-break">{!! $setting['company_address'] !!}</span><br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <div>
                                    {{ $setting['country'] . ', ' . $setting['state'] . ', ' . $setting['city'] . ', ' . $setting['zipcode'] . '.' }}
                                </div>
                            @endif
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b>{{ $setting['company_phone'] }}<br>
                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                <div><b>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</b>{{ $setting['fax_no'] }}
                                </div>
                            @endif
                        </td>
                        <td class="py-1" style=" overflow:hidden; word-wrap: break-word; word-break: break-all;">
                            <b>{{ __('messages.common.name') . ':' }}&nbsp;</b>{{ $client->user->full_name }}<br>
                            <b>{{ __('messages.common.email') . ':' }}&nbsp;</b>
                            <div class="" style="width:200px; word-break: break-all!important; ">
                                {{ $client->user->email }}</div>

                            @if (!empty($client->address))
                                <b>{{ __('messages.common.address') . ':' }}&nbsp;</b>{{ $client->address }}
                            @endif
                            @if (!empty($client->vat_no))
                                <br><b>{{ getVatNoLabel() . ':' }}&nbsp;</b>{{ $client->vat_no }}
                            @endif
                        </td>
                        <td class="py-1" style="">
                            <div class="text-nowrap font-color-gray">{{ __('messages.invoice.invoice_id') . ':' }}
                                &nbsp;#{{ $invoice->invoice_id }}</div>
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.invoice.invoice_date') . ':' }}</strong>
                                <p>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.invoice.due_date') . ':' }}</strong>
                                <p>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="py-1" style="width:5%;">#</th>
                            <th class="py-1 text-uppercase">{{ __('messages.product.product') }}</th>
                            <th class="py-1 text-uppercase" style="width:9%;"> {{ __('messages.invoice.qty') }}</th>
                            <th class="py-1 text-uppercase text-nowrap" style="width:13%;">
                                {{ __('messages.product.unit_price') }}</th>
                            <th class="py-1 text-uppercase text-nowrap" style="width:12%;">
                                {{ __('messages.invoice.tax') . '(in %)' }}
                            </th>
                            <th class="py-1 text-uppercase number-align text-nowrap" style="width:14%;">
                                {{ __('messages.invoice.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($invoice) && !empty($invoice))
                            @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                <tr class="">
                                    <td>{{ $key + 1 }}</td>
                                    @if (
                                        !empty($invoiceItems->product->description) &&
                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                        <td style="width: 30%!important;" class="py-0">
                                        @else
                                        <td>
                                    @endif
                                    <p class="m-0"
                                        style="width:150px!important;word-wrap: break-word;
                                word-break: break-all;">
                                        {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                    </p>
                                    @if (
                                        !empty($invoiceItems->product->description) &&
                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                        <span
                                            style="font-size: 12px; word-break: break-all">{{ $invoiceItems->product->description }}</span>
                                    @endif
                                    </td>
                                    <td class="text-start text-nowrap">{{ number_format($invoiceItems->quantity, 2) }}
                                    </td>
                                    <td class="text-start text-nowrap"><b
                                            class="euroCurrency">{{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                    </td>
                                    <td class="text-center">
                                        @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                            {{ $tax->tax ?? __('messages.common.tax_n/a') }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="number-align text-nowrap"><b
                                            class="euroCurrency">{{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{-- <div class="row"> --}}
            <table class="mb-4  w-100">
                <tr>
                    <td class="w-75">
                        @if (!empty($invoice->paymentQrCode))
                            <div style="">
                                <strong
                                    style="font-size: ; margin-right: 142px"><b>{{ __('messages.payment_qr_codes.payment_qr_code') }}</b></strong><br>
                                <img class="mt-2 ml-3" src="{{ $invoice->paymentQrCode->qr_image }}" height="110"
                                    width="110">
                            </div>
                        @endif
                    </td>
                    <td class="w-25 text-end">
                        <table class="">
                            <tbody class="text-end">
                                <tr>
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                    </td>
                                    <td class="euroCurrency">
                                        {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                    </td>
                                    <td class="text-nowrap">
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
                                    <td class="left" style="padding-right: 30px">
                                        <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                    </td>

                                    <td class="text-nowrap">
                                        {!! numberFormat($totalTaxes) != 0
                                            ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                            : __('messages.common.n/a') !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold" style="padding-right: 30px">
                                        {{ __('messages.invoice.total') . ': ' }}</td>
                                    <td class="text-nowrap">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold text-nowrap" style="padding-right: 30px">
                                        {{ __('messages.admin_dashboard.total_due') . ': ' }}</td>
                                    <td class="text-nowrap" {{ $styleCss }}="color: red">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-end text-nowrap" style="padding-right: 30px">
                                        {{ __('messages.admin_dashboard.total_paid') . ': ' }}
                                    </td>
                                    <td class="text-nowrap" {{ $styleCss }}="color: green">
                                        <b
                                            class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            {{-- </div> --}}
            <div class="alert alert-primary text-muted" role="alert">
                <b class="text-dark">{{ __('messages.client.notes') . ':' }}</b> {!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
            </div>
            <div class="alert alert-light text-muted" role="alert">
                <b class="text-dark">{{ __('messages.invoice.terms') . ':' }}</b> {!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}
            </div>
        </div>
    </div>

</body>

</html>
