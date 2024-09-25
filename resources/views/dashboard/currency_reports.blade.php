@extends('layouts.app')
@section('title')
    {{ __('messages.currency_reports') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-xxl-4 col-xl-4 col-sm-6">
                            <h1 class="mb-5 text-center">{{ __('messages.admin_dashboard.total_amount') }}</h1>
                            <div class="d-flex align-items-center flex-column">
                                @if (count($currencyData['totalInvoices']) > 0)
                                    @foreach ($currencyData['totalInvoices'] as $currencyId => $amount)
                                        <div
                                            class="bg-success shadow-md rounded-10 custom-widget custom-widget d-flex align-items-center justify-content-center my-3 w-65">
                                            <div class="d-flex align-items-center text-white">
                                                <h2 class="fs-1-xxl fw-bolder text-white mb-0">
                                                    {{ getInvoiceCurrencyAmount($amount, $currencyId) }}
                                                </h2>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_amount_yet') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-sm-6 mt-sm-0 mt-5">
                            <h1 class="mb-5 text-center">{{ __('messages.admin_dashboard.total_paid') }}</h1>
                            <div class="d-flex align-items-center flex-column">
                                @if (count($currencyData['paidInvoices']) > 0)
                                    @foreach ($currencyData['paidInvoices'] as $currencyId => $amount)
                                        <div
                                            class="bg-info shadow-md rounded-10 custom-widget d-flex align-items-center justify-content-center my-3 w-65">
                                            <div class="d-flex align-items-center text-white">
                                                <h2 class="fs-1-xxl fw-bolder text-white mb-0">
                                                    {{ getInvoiceCurrencyAmount($amount, $currencyId) }}
                                                </h2>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_paid_yet') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-sm-6 mt-md-0 mt-sm-0 mt-5 mt-lg-5 mt-md-5 mt-xl-0">
                            <h1 class="mb-5 text-center">{{ __('messages.admin_dashboard.total_due') }}</h1>
                            <div class="d-flex align-items-center flex-column">
                                @if (count($currencyData['dueInvoices']) > 0)
                                    @foreach ($currencyData['dueInvoices'] as $currencyId => $dueInvoiceAmount)
                                        <div
                                            class="bg-yellow-300 shadow-md rounded-10 custom-widget d-flex align-items-center justify-content-center my-3 w-65">
                                            <div class="d-flex align-items-center text-white">
                                                <h2 class="fs-1-xxl fw-bolder text-white mb-0">
                                                    {{ getInvoiceCurrencyAmount($dueInvoiceAmount, $currencyId) }}
                                                </h2>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fs-3 mt-9">{{ __('messages.invoice.nothing_due_yet') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
