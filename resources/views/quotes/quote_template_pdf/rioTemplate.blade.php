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
            margin-bottom: 30px !important;
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
    <div style="width: 100%;" style="margin-top:-40px !important;">
        <div class="d" id="boxes" style="width: 100%;">
            <table class="mb-8" style="width: 100%;">
                <tr>
                    <td style="vertical-align:top; width: 35%;" class="pt-5">
                        <img width="100px" src="{{ getLogoUrl($quote->tenant_id) }}" alt="">
                    </td>
                    <td style="width: 35%;" class="pt-5">
                        <p class="p-text mb-0">{{ __('messages.quote.quote_id') . ':' }}&nbsp;
                            <strong>#{{ $quote->quote_id }}</strong>
                        </p>
                        <p class="p-text mb-0">{{ __('messages.quote.quote_date') . ':' }}
                            <strong>{{ \Carbon\Carbon::parse($quote->invoice_date)->translatedFormat(currentDateFormat()) }}</strong>
                        </p>
                        <p class="p-text mb-0">{{ __('messages.quote.due_date') . ':' }}&nbsp;
                            <strong>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}</strong>
                        </p>
                    </td>
                    <td class="in-w-4 pt-5"
                        {{ $styleCss }}="background-color: {{ $quote_template_color }}; width: 30%;">
                        <h1 class="fancy-title tu text-center mb-auto p-3" style="color:white;  font-size: 34px">
                            <b>{{ __('messages.quote.quote_name') }}</b>
                        </h1>
                    </td>
                </tr>
            </table>
            <table style="width:70%;" class="mb-8 mt-5">
                <tr>
                    <td class="w-50" style="vertical-align:top !important;">
                        <p class="fs-6 mb-2"><strong>{{ __('messages.common.to') . ':' }}</strong></p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.name') . ':' }} <span
                                class="text-dark fw-bold">{{ $client->user->full_name }}</span>
                        </p>
                        <p class=" m-0 font-color-gray fs-6">{{ __('messages.common.email') . ':' }} <span
                                class="text-dark fw-bold">{{ $client->user->email }}</span></p>
                        <p class="m-0 font-color-gray fs-6">{{ __('messages.common.address') . ':' }} <span
                                class="text-dark fw-bold">{{ $client->address }}</span></p>
                        @if (!empty($client->vat_no))
                            <p class="m-0 font-color-gray fs-6">{{ getVatNoLabel() . ':' }} <span
                                    class="text-dark fw-bold">{{ $client->vat_no }}</span></p>
                        @endif
                    </td>
                    <td class="w-50">
                        <p class="fs-6 mb-2"><strong>{{ __('messages.common.from') . ':' }}</strong></p>
                        <p class=" m-0 font-color-gray fs-6">{{ __('messages.common.address') . ':' }}&nbsp;
                            <span class="text-dark fw-bold">{!! $setting['company_address'] !!}
                            </span>
                        </p>
                        <p class=" m-0 font-color-gray fs-6">{{ __('messages.user.phone') . ':' }}<span
                                class="text-dark fw-bold">{{ $setting['company_phone'] }}</span></p>
                    </td>
                </tr>
            </table>
            <div class="table-responsive-sm table-striped mt-5" style="width: 100%;">
                <table style="width: 100%;">
                    <thead {{ $styleCss }}="background-color: {{ $quote_template_color }}; ">
                        <tr>
                            <th class="px-2 py-1 text-white text-center fw-bold" style="width: 7%;">#</th>
                            <th class="px-2 py-1 text-white in-w-2 fw-bold text-uppercase">
                                {{ __('messages.product.product') }}</th>
                            <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                                style="width: 12%;">
                                {{ __('messages.invoice.qty') }}</th>
                            <th class="px-2 py-1 text-white text-center fw-bold text-uppercase text-nowrap"
                                style="width: 18%;">
                                {{ __('messages.product.unit_price') }}</th>
                            <th class="px-2 py-1 text-white text-end fw-bold text-uppercase text-nowrap"
                                style="width: 18%;">
                                {{ __('messages.invoice.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($quote) && !empty($quote))
                            @foreach ($quote->quoteItems as $key => $quoteItems)
                                <tr class="border-b-gray">
                                    <td class="p-2 text-center bg-gray fw-bold">{{ $key + 1 }}</td>
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
                                    <td class="p-2 text-center fw-bold text-nowrap">
                                        {{ $quoteItems->quantity }}
                                    </td>
                                    <td class="p-2 text-center bg-gray fw-bold text-nowrap euroCurrency">
                                        {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                    </td>

                                    <td class="p-2 text-end bg-gray fw-bold text-nowrap euroCurrency">
                                        {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
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
                            <td class="p-2 text-center fw-bold text-nowrap">
                                {{ __('messages.quote.amount') . ':' }}</td>
                            <td class="p-2 text-end bg-gray fw-bold text-nowrap">
                                {{ getCurrencyAmount($quote->amount, true) }}</td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="p-2 text-center fw-bold text-nowrap">{{ __('messages.invoice.discount') . ':' }}
                            </td>
                            <td class="p-2 text-end bg-gray fw-bold text-nowrap">
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
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="p-2 text-center fw-bold text-nowrap text-nowrap">
                                <strong>{{ __('messages.quote.total') . ':' }}</strong>
                            </td>
                            <td class="p-2 text-end text-white fw-bold text-nowrap euroCurrency"
                                {{ $styleCss }}="background-color: {{ $quote_template_color }}; ">
                                {{ getCurrencyAmount($quote->final_amount, true) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-5 mb-5">
                <h6 class="d-fancy-title mb5">{{ __('messages.client.notes') . ':' }}</h6>
                <p class="font-color-gray">
                    {!! nl2br($quote->note ?? __('messages.common.n/a')) !!}</p>
            </div>

            <table class="w-100">
                <tr>
                    <td class="w-75">
                        <div class="mb-8">
                            <h6 class="d-fancy-title mb5">{{ __('messages.invoice.terms') . ':' }}</h6>
                            <p class="font-color-gray">
                                {!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</p>
                        </div>

                    </td>
                    <td class="w-25 text-end">
                        <div class="">
                            <h6 class="d-fancy-title mb5" {{ $styleCss }}="color: {{ $quote_template_color }}">
                                {{ __('messages.setting.regards') . ':' }}</h6>
                            <p class="font-color-gray">
                                <b>{{ getAppName($quote->tenant_id) }}</b>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
