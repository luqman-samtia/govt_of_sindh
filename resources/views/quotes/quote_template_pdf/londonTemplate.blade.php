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

<body style="padding: 0rem 2rem;">
    @php $styleCss = 'style'; @endphp
    <div>
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="" style="margin-top:-40px !important;">
                    <div class="header-section " {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                        <table class="w-100 pt-5">
                            <tr>
                                <td class="bg-gray-100">
                                    <div class="px-3">
                                        <img width="100px" src="{{ getLogoUrl($quote->tenant_id) }}" alt="">
                                    </div>
                                </td>
                                <td class="bg-black invoice-text" style="width:45%;">
                                    <div class="number-align">
                                        <h1 class="m-0 p-3" style="color:white;  font-size: 34px">
                                            <b> {{ __('messages.quote.quote_name') }}</b>
                                        </h1>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-white number-align px-3 py-2 fs-6">
                                    <strong>#{{ $quote->quote_id }}</strong>
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
                                <p class=" m-0 font-color-gray fs-6">
                                    <strong>{{ __('messages.user.phone') . ':' }}&nbsp;</strong><span
                                        class="text-color">{{ $setting['company_phone'] }}</span>
                                </p>
                            </td>
                            <td width="33.33%;" class="ps-sm-5rem">
                                <p class="fs-6 mb-2"><strong>{{ __('messages.common.to') . ':' }}</strong></p>
                                <p class="m-0 font-color-gray fs-6"><strong>{{ __('messages.common.name') . ':' }}
                                    </strong> {{ $client->user->full_name }}</p>
                                <p class=" m-0 font-color-gray fs-6">
                                    <strong>{{ __('messages.common.email') . ':' }}</strong>
                                    {{ $client->user->email }}
                                </p>
                                <p class="m-0 font-color-gray fs-6">
                                    <strong>{{ __('messages.common.address') . ':' }}&nbsp;
                                    </strong>{{ $client->address }}
                                </p>
                                @if (!empty($client->vat_no))
                                    <p class="m-0 font-color-gray fs-6">
                                        <strong>{{ getVatNoLabel() . ':' }}&nbsp;
                                        </strong>{{ $client->vat_no }}
                                    </p>
                                @endif
                            </td>
                            <td width="33.33%;" class="number-align">
                                <p class="mb-2 font-color-gray fs-6">
                                    <strong>{{ __('messages.quote.quote_date') . ':' }}&nbsp;</strong>
                                    {{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                                <p class="  font-color-gray fs-6">
                                    <strong>{{ __('messages.quote.due_date') . ':' }}&nbsp;
                                    </strong>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="border-b-gray w-100 mt-5">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2" style="width:5% !important;">#</th>
                            <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                            <th class="p-2 text-center text-uppercase" style="width:15% !important;">
                                {{ __('messages.invoice.qty') }}
                            </th>
                            <th class="p-2 text-center text-uppercase text-nowrap" style="width:18% !important;">
                                {{ __('messages.product.unit_price') }}</th>
                            <th class="p-2 number-align text-uppercase text-nowrap" style="width:18% !important;">
                                {{ __('messages.invoice.amount') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($quote) && !empty($quote))
                            @foreach ($quote->quoteItems as $key => $quoteItems)
                                <tr>
                                    <td class="p-2" style="width:5%;"><span>{{ $key + 1 }}</span></td>
                                    <td class="p-2 in-w-2">
                                        <p class="fw-bold mb-0">
                                            {{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                        </p>
                                        @if (
                                            !empty($invoiceItems->product->description) &&
                                                (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                            <span
                                                style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-center" style="width:15%;">
                                        {{ $quoteItems->quantity }}</td>
                                    <td class="p-2 text-center text-nowrap euroCurrency" style="width:18%;">
                                        {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                    </td>
                                    <td class="p-2 number-align text-nowrap euroCurrency" style="width:18%;">
                                        {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="w-100">
                    <tr>
                        <td style="width: 55% !important"></td>
                        <td style="width: 45% !important">
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                                        </td>
                                        <td
                                            class="euroCurrency number-align font-color-gray py-1 px-2 fw-bold text-nowrap">
                                            {{ getCurrencyAmount($quote->amount, true) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 px-2 text-nowrap">
                                            <strong>{{ __('messages.quote.discount') . ':' }}</strong>
                                        </td>
                                        <td class="number-align font-color-gray py-1 px-2 fw-bold text-nowrap">
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
                                <tfoot class="text-white"
                                    {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                    <tr>
                                        <td class="p-2 text-nowrap">
                                            <strong>{{ __('messages.quote.total') . ':' }}</strong>
                                        </td>
                                        <td class="number-align p-2 text-nowrap euroCurrency">
                                            <strong>{{ getCurrencyAmount($quote->final_amount, true) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="" style="margin-top:3rem;">
                    <h6 class="d-fancy-title mb5">{{ __('messages.client.notes') . ':' }}</h6>
                    <p class="font-color-gray">{!! nl2br($quote->note ?? __('messages.common.n/a')) !!}
                    </p>
                    <table class="w-100">
                        <tr>
                            <td class="w-75">
                                <div class="mb-8">
                                    <h6 class="d-fancy-title mb5">{{ __('messages.invoice.terms') . ':' }}</h6>
                                    <p class="font-color-gray">{!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</p>
                                </div>
                            </td>
                            <td class="w-25 number-align">
                                <div class="number-align">
                                    <h6 class="d-fancy-title mb5">{{ __('messages.setting.regards') . ':' }}</h6>
                                    <p class="fw-bold text-purple"
                                        {{ $styleCss }}="color:
                                    {{ $quote_template_color }}">
                                        {{ getAppName($quote->tenant_id) }}</p>
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
