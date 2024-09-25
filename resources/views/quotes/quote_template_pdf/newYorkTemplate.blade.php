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

        @if (getCurrencySymbol($quote->tenant_id) == '€')
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
                        <div class="companylogo"><img width="100px" src="{{ getLogoUrl($quote->tenant_id) }}"
                                alt=""></div>
                    </td>
                    <td>
                        <div class="invoice-header-inner">
                            <h3 {{ $styleCss }}="color: {{ $quote_template_color }}">
                                <b>{{ __('messages.quote.quote_name') }}</b>
                            </h3>
                            <span class="text-color">#{{ $quote->quote_id }}</span>
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
                                <strong class="font-size-15">{{ __('messages.quote.quote_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                            <div class="">
                                <strong class="font-size-15">{{ __('messages.quote.due_date') . ':' }}</strong>
                                <p class="text-color">
                                    {{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </div>
                        </td>
                        <td class="billedto"
                            style="vertical-align:top !important; width:33.33% !important; overflow:hidden; word-wrap: break-word; word-break: break-all;">
                            <b>{{ __('messages.common.to') . ':' }}</b><br>
                            <span><b>{{ __('messages.common.name') . ':' }}&nbsp;</b></span> <span
                                class="text-color">{{ $client->user->full_name }}</span><br>
                            <span><b>{{ __('messages.common.email') . ':' }}&nbsp;</b></span>
                            <span class="text-color">{{ $client->user->email }}</span><br>
                            <span><b>{{ __('messages.common.address') . ':' }}&nbsp;</b></span>
                            <span class="text-color">{{ $client->address }}</span><br>
                            @if (!empty($client->vat_no))
                                <span><b>{{ getVatNoLabel() . ':' }}&nbsp;</b></span>
                                <span class="text-color">{{ $client->vat_no }}</span><br>
                            @endif
                        </td>
                        <td class="from" style="vertical-align:top !important; width:33.33% !important;">
                            <b>{{ __('messages.common.from') . ':' }}</b><br>
                            <b>{{ __('messages.common.address') . ':' }}&nbsp;</b><span
                                class="text-break text-color">{!! $setting['company_address'] !!}</span><br>
                            <b>{{ __('messages.user.phone') . ':' }}&nbsp;</b><span
                                class="text-color">{{ $setting['company_phone'] }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="content">
            <table class="table product-table w-100"
                {{ $styleCss }}="border-top: 1px solid {{ $quote_template_color }}
                ;border-bottom: 1px solid {{ $quote_template_color }}">
                <thead class="bg-light"
                    {{ $styleCss }}="border-top: 1px solid {{ $quote_template_color }}
                ;border-bottom: 1px solid {{ $quote_template_color }}">
                    <tr>
                        <th style="width:5%;">#</th>
                        <th class="text-uppercase">{{ __('messages.product.product') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:14%;">
                            {{ __('messages.invoice.qty') }}</th>
                        <th class="text-center text-uppercase text-nowrap" style="width:16%;">
                            {{ __('messages.product.unit_price') }}</th>
                        <th class="text-end text-uppercase text-nowrap" style="width:16%;">
                            {{ __('messages.invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @if (isset($quote) && !empty($quote))
                        @foreach ($quote->quoteItems as $key => $quoteItems)
                            <tr>
                                <td>
                                    {{ $key + 1 }}</td>
                                <td>
                                    <b>{{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}</b>
                                    <p style="margin:0;" class="text-color">
                                        @if (!empty($invoiceItems->product->description) &&
                                        (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                            <span
                                                style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center text-color text-nowrap">
                                    {{ $quoteItems->quantity }}</td>
                                <td class="text-center text-color text-nowrap euroCurrency">
                                    {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                </td>
                                <td class="number-align text-color euroCurrency text-nowrap">
                                    {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
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

                </td>
                <td class="text-end" style="width:35%;">
                    <table class="total-table table w-100">
                        <tbody class="">
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color euroCurrency">
                                    {{ getCurrencyAmount($quote->amount, true) }}
                                </td>
                            </tr>
                            <tr class="border-bottom-gray">
                                <td class="left">
                                    <strong>{{ __('messages.quote.discount') . ':' }}</strong>
                                </td>
                                <td class="text-end text-color">
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
                            <tr class="border-bottom-gray">
                                <td class="font-weight-bold">
                                    {{ __('messages.quote.total') . ':' }}
                                </td>
                                <td class="text-nowrap text-end text-color euroCurrency">
                                    {{ getCurrencyAmount($quote->final_amount, true) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="notes-terms">
            <p><b>{{ __('messages.client.notes') . ':' }}</b><br><span class="text-color">
                    {!! nl2br($quote->note ?? __('messages.common.n/a')) !!}</span></p>
            <p><b>{{ __('messages.invoice.terms') . ':' }}</b><br>
                <span class="text-color">
                    {!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</span>
            </p>
        </div>
        <div class="regards">
            <p><b>{{ __('messages.setting.regards') . ':' }}</b><br>
                <b {{ $styleCss }}="color: {{ $quote_template_color }}">{{ getAppName($quote->tenant_id) }}</b>
            </p>
        </div>
    </div>
</body>

</html>
