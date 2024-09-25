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

        @if (getInvoiceCurrencySymbol($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body>
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview mumbai-template">
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="top-border" style="background-color: {{ $invoice_template_color }};"></div>
                <div style="background-color: {{ $invoice_template_color }};">
                    <table class="pb-10 bg-white w-100 m-0 h-125px" style="overflow:hidden;">
                        <tr>
                            <td class=" p-0 m-0 " style="width:66%; overflow:hidden !important;  ">
                                <div class="bg-white  h-125px" style=" border-top-right-radius:30px; padding:24.5px;">
                                    <img width="100px" height="66px" src="{{ getLogoUrl($invoice->tenant_id) }}"
                                        class="img-logo">
                                </div>
                            </td>
                            <td class="bg-white p-0 m-0 h-125px"
                                style="width:33%;  border-bottom-left-radius:30px; overflow:hidden;">
                                <div class="text-end p-4 pt-10 h-125px"
                                    style=" background-color: {{ $invoice_template_color }};">
                                    <h1 class="m-0 pt-4 text-white pe-2"
                                        style=" font-size: 36px; font-weight:700; letter-spacing: 4px;">
                                        {{ __('messages.common.invoice') }}</h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="px-4 py-4 bg-white" style="margin-right:10px;">
                        <div class="pb-3">
                            <table class="mb-10 w-100">
                                <tbody>
                                    <tr style="vertical-align:top;">
                                        <td width="33.33%">
                                            <p class="fs-6 mb-2 font-gray-900">
                                                <strong>{{ __('messages.common.to') . ':' }}</strong>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.name') . ':' }}
                                                <span class="font-gray-900">{{ $client->user->full_name }}</span>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.email') . ':' }}
                                                <span class="font-gray-900">{{ $client->user->email }}</span>
                                            </p>
                                            <p class=" mb-1  font-color-gray fs-6">
                                                {{ __('messages.common.address') . ':' }}
                                                <span class="font-gray-900">{{ $client->address }} </span>
                                            </p>
                                            @if (!empty($client->vat_no))
                                                <p class=" mb-1  font-color-gray fs-6">
                                                    {{ getVatNoLabel() . ':' }}
                                                    <span class="font-gray-900">{{ $client->vat_no }} </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td width="33.33%">
                                            <p class="fs-6 mb-2 font-gray-900">
                                                <strong>{{ __('messages.common.from') . ':' }}</strong>
                                            </p>
                                            <p class=" mb-1 font-color-gray fw-bold fs-6">
                                                {{ __('messages.common.address') . ':' }}&nbsp; <span
                                                    class="font-gray-900">{!! $setting['company_address'] !!}</span></p>
                                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                                <p class=" m-0 font-color-gray fs-6">
                                                    {{ $setting['zipcode'] . ',' . $setting['city'] . ', ' . $setting['state'] . ', ' . $setting['country'] }}
                                                </p>
                                            @endif
                                            <p class=" mb-1 font-color-gray  fw-bold fs-6">
                                                {{ __('messages.user.phone') . ':' }}&nbsp; <span
                                                    class="font-gray-900">{{ $setting['company_phone'] }}</span></p>
                                            @if (isset($setting['show_additional_address_in_invoice']) && $setting['show_additional_address_in_invoice'] == 1)
                                                <p class="mb-1 font-color-gray  fw-bold fs-6">
                                                    <strong>{{ __('messages.invoice.fax_no') . ':' }}&nbsp;</strong><span
                                                        class="font-gray-900">{{ $setting['fax_no'] }}</span>
                                                <p>
                                            @endif
                                        </td>
                                        <td width="33.33%" class="text-end pt-7">
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
                        <div class="overflow-auto">
                            <table class="invoice-table w-100">
                                <thead style="background-color: {{ $invoice_template_color }};">
                                    <tr>
                                        <th class="p-2 text-uppercase" style="width:5% !important;">#</th>
                                        <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}
                                        </th>
                                        <th class="p-2 text-center text-uppercase" style="width:9% !important;">
                                            {{ __('messages.invoice.qty') }}
                                        </th>
                                        <th class="p-2 text-center  text-nowrap text-uppercase"
                                            style="width:15% !important;">
                                            {{ __('messages.product.unit_price') }}</th>
                                        <th class="p-2 text-center  text-nowrap text-uppercase"
                                            style="width:13% !important;">
                                            {{ __('messages.invoice.tax') . '(in %)' }}
                                        </th>
                                        <th class="p-2 text-end  text-nowrap text-uppercase"
                                            style="width:14% !important;">
                                            {{ __('messages.invoice.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($invoice) && !empty($invoice))
                                        @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                            <tr>
                                                <td class="p-2" style="width:5%;">
                                                    <span>{{ $key + 1 }}</span>
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
                                                    {{ number_format($invoiceItems->quantity, 2) }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    @foreach ($invoiceItems->invoiceItemTax as $keys => $tax)
                                                        {{ $tax->tax ?? '--' }}
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="p-2 text-end">
                                                    {{ isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="my-5">
                            <table class="w-100">
                                <tr>
                                    <td style="vertical-align:bottom; width:60%;">
                                        @if (!empty($invoice->paymentQrCode))
                                            <img class="mt-4" src="{{ $invoice->paymentQrCode->qr_image }}"
                                                height="100" width="100">
                                        @endif
                                    </td>
                                    <td style="vertical-align:top; width:40%;">
                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.invoice.sub_total') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
                                                        {{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
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
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
                                                        {!! numberFormat($totalTaxes) != 0
                                                            ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                            : __('messages.common.n/a') !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.invoice.total') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
                                                        {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total-amount"
                                                {{ $styleCss }}="background-color: {{ $invoice_template_color }};">
                                                <tr>
                                                    <td class="p-2">
                                                        <strong>{{ __('messages.admin_dashboard.total_due') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end p-2">
                                                        <strong>
                                                            {{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="" style="position: relative;">
                            <div class="pt-10">
                                <h6 class="font-gray-900 mb5"><b><?php echo __('messages.client.notes'); ?>:</b></h6>
                                <p class="font-gray-600">{!! nl2br($invoice->note ?? __('messages.common.not_available')) !!}
                                </p>
                            </div>
                            <div class="" style="width:60%; margin-bottom:-100px;">
                                <h6 class="font-gray-900">
                                    <b>{{ __('messages.invoice.terms') . ':' }}</b>
                                </h6>
                                <p class="font-gray-600 mb-0">{!! nl2br($invoice->term ?? __('messages.common.not_available')) !!}</p>
                            </div>
                            <div class=" text-end" style=" position: relative; top:0px;right:0;">
                                <div class="">
                                    <h5 class="text-indigo "
                                        {{ $styleCss }}="color:{{ $invoice_template_color }}">
                                        <b>{{ __('messages.setting.regards') . ':' }}</b>
                                    </h5>
                                    <p class="fs-6"><b> {{ getAppName() }}</b></p>
                                </div>
                                </td>
                            </div>
                        </div>

                    </div>
                    <div>
                        <table class="w-100 bg-white">
                            <tr>
                                <td class="p-0 h-25px" style="width:80% !important; overflow:hidden;">
                                    <div class="bg-white h-25px" style=" border-bottom-right-radius:30px; ;">
                                    </div>
                                </td>
                                <td class="bg-white p-0 h-25px"
                                    style="width:20%; border-top-left-radius:35px; overflow:hidden;">
                                    <div class="text-end h-25px"
                                        style="background-color: {{ $invoice_template_color }};  padding:26px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="bottom-border" style="background-color: {{ $invoice_template_color }}"></div>
                </div>
            </div>
        </div>
</body>

</html>
