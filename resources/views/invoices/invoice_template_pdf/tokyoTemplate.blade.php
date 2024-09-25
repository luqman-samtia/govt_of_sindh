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
    <div class="preview-main client-preview tokyo-template">
        <div class="d" id="boxes">
            <div class="">
                <table class="mb-3 w-100">
                    <tr>
                        <td class="">
                            <img width="100px" src="{{ getLogoUrl($invoice->tenant_id) }}" class="img-logo">
                        </td>
                        <td class="heading-text">
                            <div class="text-end">
                                <h1 class="m-0 text-black"
                                    {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                    <?php echo __('messages.common.invoice'); ?></h1>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="">
                    <table class="my-3 w-100">
                        <tbody>
                            <tr style="vertical-align:top;">
                                <td width="43.33%;">
                                    <p class="fs-6 mb-2 font-gray-900">
                                        <strong>{{ __('messages.common.to') . ':' }}</strong>
                                    </p>
                                    <p class=" mb-1 font-color-gray fs-6">{{ __('messages.common.name') . ':' }} <span
                                            class="font-gray-900">{{ $client->user->full_name }}</span></p>
                                    <p class="mb-1 font-color-gray fs-6">{{ __('messages.common.email') . ':' }}
                                        <span class="font-gray-900">{{ $client->user->email }}</span>
                                    </p>
                                    <p class="mb-1 font-color-gray fs-6">{{ __('messages.common.address') . ':' }}
                                        <span class="font-gray-900">{{ $client->address }} </span>
                                    </p>
                                    @if (!empty($client->vat_no))
                                        <p class="mb-1 font-color-gray fs-6">{{ getVatNoLabel() . ':' }}
                                            <span class="font-gray-900">{{ $client->vat_no }} </span>
                                        </p>
                                    @endif
                                </td>
                                <td width="23.33%;">
                                    <p class="fs-6 mb-2 font-gray-900">
                                        <strong>{{ __('messages.common.from') . ':' }}</strong>
                                    </p>
                                    <p class=" mb-1 font-color-gray fs-6">
                                        {{ __('messages.common.address') . ':' }}&nbsp; <span
                                            class="font-gray-900">{!! $setting['company_address'] !!}</span></p>
                                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                        <p class=" m-0 font-gray-900 fs-6">
                                            {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                        </p>
                                    @endif
                                    <p class=" mb-1 font-color-gray fs-6">
                                        {{ __('messages.user.phone') . ':' }}&nbsp; <span
                                            class="font-gray-900">{{ $setting['company_phone'] }}</span></p>
                                    @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                        <p class="mb-1 font-gray-600 fs-6">
                                            {{ __('messages.invoice.fax_no') . ':' }}&nbsp;<span
                                                class="font-gray-900">{{ $setting['fax_no'] }}</span>
                                        <p>
                                    @endif
                                </td>
                                <td width="33.33%;" class="text-end pt-7">
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
                </div>
                <div class="w-100 overflow-auto">
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
                                        <td class="" style="width:5%;"><span>{{ $key + 1 }}</span></td>
                                        <td class=" in-w-2">
                                            <p class="fw-bold mb-0">
                                                {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                            </p>
                                            @if (
                                                !empty($invoiceItems->product->description) &&
                                                    (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                <span class=""
                                                    style="font-size: 12px; word-break: break-all !important">{{ $invoiceItems->product->description }}</span>
                                            @endif
                                        </td>
                                        <td class=" text-center text-nowrap">
                                            {{ number_format($invoiceItems->quantity, 2) }}
                                        </td>
                                        <td class=" text-center text-nowrap">
                                            {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                                {{ $tax->tax ?? '--' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-end text-nowrap">
                                            {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="my-4">
                    <table class="ms-auto mb-10 text-end w-100">
                        <tr>
                            <td class="w-75"></td>
                            <td class="w-25">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="py-1 px-0 font-dark-gray text-nowrap">
                                                <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                            </td>
                                            <td class="text-end font-gray-600 py-1 px-0 text-nowrap">
                                                {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-1 px-0 font-dark-gray text-nowrap">
                                                <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                            </td>
                                            <td class="text-end font-gray-600 py-1 px-0 text-nowrap">
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
                                            <td class="pt-1 pb-2 px-0 font-dark-gray text-nowrap">
                                                <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                            </td>
                                            <td class="text-end font-gray-600 pt-1 pb-2 px-0 text-nowrap">
                                                {!! numberFormat($totalTaxes) != 0
                                                    ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                    : __('messages.common.n/a') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-1 px-0 font-dark-gray text-nowrap">
                                                <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                            </td>
                                            <td class="text-end font-gray-600 py-1 px-0 text-nowrap">
                                                {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="total-amount">
                                        <tr>
                                            <td class="py-2 font-dark-gray text-nowrap">
                                                <strong>{{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                            </td>
                                            <td class="text-end font-dark-gray py-2 fw-bold text-nowrap">
                                                {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div style="vertical-align:bottom; width:60%;">
                        @if (!empty($invoice->paymentQrCode))
                            <img class="mt-4" src="{{ $invoice->paymentQrCode->qr_image }}" height="100"
                                width="100">
                        @endif
                    </div>
                </div>
                <div class="mt-20">
                    <div class="mb-5 pt-10">
                        <h6 class="font-gray-900 mb5"><b>{{ __('messages.client.notes') . ':' }}</b></h6>
                        <p class="font-gray-600">{!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
                        </p>
                    </div>
                    <table class="mb-3 w-100">
                        <tr>
                            <td class="w-50">
                                <div class="">
                                    <h6 class="font-gray-900 mb5"><b>{{ __('messages.invoice.terms') . ':' }}</b></h6>
                                    <p class="font-gray-600 mb-0">{!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}
                                    </p>
                                </div>
                            </td>
                            <td class="w-25 text-end">
                                <div class="">
                                    <h6 class="font-dark-gray mb5"><b>{{ __('messages.setting.regards') . ':' }}</b>
                                    </h6>
                                    <p class="fs-6"
                                        {{ $styleCss }}="color:
                                    {{ $invoice_template_color }}">
                                        {{ html_entity_decode($setting['app_name']) }}</p>
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
