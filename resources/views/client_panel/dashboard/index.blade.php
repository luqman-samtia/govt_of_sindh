@extends('client_panel.layouts.app')
@section('title')
    {{ __('messages.dashboard') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        {{-- Total Invoices Amount Widget --}}
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.currency.reports') }}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-center my-3">
                                    <div class="text-white mt-3 text-center">
                                        <h2 class="fs-1-xxl fw-bolder text-white">
                                            {{ __('messages.admin_dashboard.total_amount') }}
                                        </h2>
                                        <span class="text-white">{{ __('messages.common.click_here') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {{-- Recieved Amount Widget --}}
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.currency.reports') }}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-center my-3">
                                    <div class="text-white mt-3 text-center">
                                        <h2 class="fs-1-xxl fw-bolder text-white">
                                            {{ __('messages.admin_dashboard.total_paid') }}
                                        </h2>
                                        <span class="text-white">{{ __('messages.common.click_here') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {{-- Partially Paid Widget --}}
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.currency.reports') }}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-center my-3">
                                    <div class="text-white mt-3 text-center">
                                        <h2 class="fs-1-xxl fw-bolder text-white">
                                            {{ __('messages.admin_dashboard.total_due') }}
                                        </h2>
                                        <span class="text-white">{{ __('messages.common.click_here') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.invoices.index') }}" class="mb-xl-8 text-decoration-none">

                                <div
                                    class="bg-warning shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-yellow-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>

                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ formatTotalAmount($total_invoices) }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_invoices') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.invoices.index', ['status' => 2]) }}"
                                class="mb-xl-8 text-decoration-none">

                                <div
                                    class="bg-secondary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-gray-200 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-clipboard-check card-icon text-dark"></i>

                                    </div>
                                    <div class="text-end text-dark">
                                        <h2 class="fs-1-xxl fw-bolder text-dark">{{ formatTotalAmount($paid_invoices) }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">
                                            {{ __('messages.admin_dashboard.total_paid_invoices') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('client.invoices.index', ['status' => 1]) }}"
                                class="mb-xl-8 text-decoration-none">

                                <div
                                    class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-exclamation-triangle card-icon text-white"></i>

                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ formatTotalAmount($unpaid_invoices) }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">
                                            {{ __('messages.admin_dashboard.total_unpaid_invoices') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
