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

        @page {
            margin-top: 40px !important;
        }

        @if (getCurrencySymbol($quote->tenant_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body style="padding: 0rem 0rem !important;">
    @php $styleCss = 'style'; @endphp
    <div class="mb-8 w-100 position-relative"
        style="background-color:#F9F9F9; padding: 3rem 2rem !important;; margin-top:-40px !important;">
        <table class="w-100">
            <tr>
                <td style="vertical-align:top; width: 45% !important;">
                    <div>
                        <img width="100px" src="{{ getLogoUrl($quote->tenant_id) }}" alt="">
                    </div>
                </td>
                <td style="vertical-align:top; width: 55% !important; padding: 0rem 2rem 0rem 0rem ;">
                    <table class="w-100">
                        <thead class="">
                            <tr>
                                <th class="f-b" style="width: 50% !important;">
                                    <h4 {{ $styleCss }}="color: {{ $quote_template_color }}">
                                        <strong>{{ __('messages.quote.quote_name') }}</strong>
                                    </h4>
                                </th>
                                <th class="f-b" style="width: 50% !important;">
                                    <h4 {{ $styleCss }}="color: {{ $quote_template_color }}">
                                        #{{ $quote->quote_id }}</h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 50% !important;">
                                    <p class="m-0 fw-bold fs-6"><b>{{ __('messages.quote.quote_date') . ':' }}</b></p>
                                    <p>{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                    </p>
                                </td>
                                <td style="width: 50% !important;">
                                    <p class="m-0 fw-bold fs-6"><b>{{ __('messages.quote.due_date') . ':' }}</b></p>
                                    <p>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="pr-5"
                                    style="vertical-align:top !important; width:50% !important; overflow:hidden; word-wrap: break-word; word-break: break-all;">
                                    <p class="m-0 fw-bold fs-6"><b>{{ __('messages.common.from') }}</b></p>
                                    <address>
                                        {!! $setting['company_address'] !!}
                                    </address>
                                </td>
                                <td class="pr-3"
                                    style="vertical-align:top !important; width:49% !important; overflow:hidden; word-wrap: break-word; word-break: break-all;">
                                    <p class="m-0 fw-bold fs-6"><b>{{ __('messages.common.to') }}</b></p>
                                    <span>{{ $client->user->full_name }}</span><br>
                                    <span>{{ $client->user->email }}</span><br>
                                    <span>{{ $client->address }}</span><br>
                                    @if ($client->vat_no)
                                        <span>{{ $client->vat_no }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if (!empty($setting['company_phone']))
                                <tr>
                                    <td style=" width: 50% !important;">
                                        <p class="m-0 fw-bold fs-6"><b>{{ __('messages.user.phone') }}</b></p>
                                        <p class="m-0">
                                            {{ $setting['company_phone'] }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="table-responsive-sm p-4">
        <table class="w-100">
            <thead {{ $styleCss }}="border-bottom: 1px solid {{ $quote_template_color }}">
                <tr>
                    <th class="py-1" {{ $styleCss }}="color: {{ $quote_template_color }}; width:5%;">#</th>
                    <th class="py-1 in-w-2 text-uppercase" {{ $styleCss }}="color: {{ $quote_template_color }}">
                        {{ __('messages.product.product') }}</th>
                    <th class="py-1 text-uppercase"
                        {{ $styleCss }}="color: {{ $quote_template_color }}; width:12%;">
                        {{ __('messages.invoice.qty') }}</th>
                    <th class="py-1 text-center text-uppercase text-nowrap"
                        {{ $styleCss }}="color: {{ $quote_template_color }}; width:20%;">
                        {{ __('messages.product.unit_price') }}</th>
                    <th class="py-1 text-end text-uppercase text-nowrap"
                        {{ $styleCss }}="color: {{ $quote_template_color }}; width:19%; text-align: end !important;">
                        {{ __('messages.invoice.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($quote) && !empty($quote))
                    @foreach ($quote->quoteItems as $key => $quoteItems)
                        <tr>
                            <td class="py-1"><span>{{ $key + 1 }}</span></td>
                            <td class="py-1 in-w-2">
                                <p class="fw-bold mb-0">
                                    {{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                </p>
                                @if (!empty($invoiceItems->product->description) &&
                                        (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                    <span
                                        style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                @endif
                            </td>
                            <td class="py-1">{{ $quoteItems->quantity }}</td>
                            <td class="py-1 text-center text-nowrap euroCurrency">
                                {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                            </td>
                            <td class="py-1 text-end text-nowrap euroCurrency">
                                {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <table class="w-100  pl-3 pr-4">
        <tr>
            <td style="width:49%;"></td>
            <td {{ $styleCss }}="border-top: 1px solid {{ $quote_template_color }}; width:51%;">
                <table class="w-100">
                    <tbody>
                        <tr>
                            <td class="py-1 text-nowrap">
                                <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                            </td>
                            <td class="py-1 text-nowrap number-align text-nowrap">
                                {{ getCurrencyAmount($quote->amount, true) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 text-nowrap">
                                <strong>{{ __('messages.invoice.discount') . ':' }}</strong>
                            </td>
                            <td class="text-nowrap number-align py-1 text-nowrap">
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
                    <tfoot {{ $styleCss }}="border-top: 1px solid {{ $quote_template_color }}">
                        <tr>
                            <td class="pt-2 text-nowrap">
                                <strong>{{ __('messages.quote.total') . ':' }}</strong>
                            </td>
                            <td class="text-nowrap number-align pt-2">
                                <b class="euroCurrency">{{ getCurrencyAmount($quote->final_amount, true) }}</b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>
    <div class="p-4 mt-5">
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
