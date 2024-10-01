@extends('layouts.app')
@section('title')
    {{-- {{ __('messages.dashboard') }} --}}
    @endsection
    @section('content')

    <div class="container-fluid">
        @if(session('message'))
        <h6 class="alert alert-success">
            {{ session('message') }}
        </h6>
                @endif
                @if(session('error'))
        <h6 class="alert alert-danger">
            {{ session('error') }}
        </h6>
                @endif
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        {{-- Clients Widget --}}
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 widget">
                            <a href="{{route('total_letter')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-green-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ $total_letters }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light"> {{ __('Total Letters') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>




                        {{-- Total Invoices Widget --}}
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 widget">
                            <a href="" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ 0 }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Orders') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 widget">
                            <a href="{{route('total_draft_letter')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ $total_drafts }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Drafts') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 widget">
                            <a href="{{route('admin.dashboard')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-center text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Create Letter | Order') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Paid Widget --}}
                        {{-- <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('invoices.index', ['status' => 2]) }}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-dark shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-gray-700 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i
                                            class="fas fa-clipboard-check card-icon {{ \Illuminate\Support\Facades\Auth::user()->dark_mode ? 'text-black' : 'text-white' }}"></i>
                                    </div>
                                    <div
                                        class="text-end {{ \Illuminate\Support\Facades\Auth::user()->dark_mode ? 'text-black' : 'text-white' }}">
                                        <h2
                                            class="fs-1-xxl fw-bolder {{ \Illuminate\Support\Facades\Auth::user()->dark_mode ? 'text-black' : 'text-white' }}">
                                            {{ formatTotalAmount($paid_invoices) }}</h2>
                                        <h3 class="mb-0 fs-4 fw-light">
                                            {{ __('messages.admin_dashboard.total_paid_invoices') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div> --}}
                        {{-- Unapid Widget --}}
                        {{-- <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <a href="{{ route('invoices.index', ['status' => 1]) }}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
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
                        </div> --}}
                    </div>
                </div>
                {{-- <div class="col-12 mb-4">
                    <div class="">
                        <div class="card mt-3">
                            <div class="card-body p-5">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="mb-0">{{ __('messages.admin_dashboard.income_overview') }}</h3>
                                    <div class="ms-auto">
                                        <div id="rightData" class="date-picker-space">
                                            <input class="form-control removeFocus" id="time_range">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-lg-6 p-0">
                                    <div class="">
                                        <div id="yearly_income_overview-container" class="pt-2">
                                            <canvas id="yearly_income_chart_canvas" height="200" width="905"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-xxl-12 col-12 col-lg-12 col-md-12 mb-5 mb-xl-0">
                    <div class="card">
                        <div class="card-header pb-0 px-10">
                            <h3 class="mb-0">{{ __('') }}</h3>
                        </div>
                        <div class="card-body pt-7">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-xl-6">
                                    <h4>Create Letter</h4>
                                    <a id="gos_bg_color" style="width: 9.563rem !important" 
                                    href="#" 
                                    onclick="createLetter(event)"
                                    class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0"
                                    >
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" >
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </a>

                                </div>

                                <div class="col-md-6 col-lg-6 col-xl-6" style="text-align: end;">
                                    <h4>Create Order </h4>
                                    <a href="" id="gos_bg_color" style="width: 9.563rem !important" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0 px-5"

                                    >
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" >
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </a>
                                </div>
                            </div>
                            <div id="payment-overview-container" class="justify-align-center">
                                <canvas id="payment_overview"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xxl-6 col-12 ">
                    <div class="card">
                        <div class="card-header pb-0 px-10">
                            <h3 class="mb-0">{{ __('') }}</h3>
                        </div>
                        <div class="card-body pt-7">
                            <div id="invoice-overview-container" class="justify-align-center">
                                <canvas id="invoice_overview"></canvas>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- {{ Form::hidden('currency', getCurrencySymbol(), ['id' => 'currency']) }} --}}
    {{-- {{ Form::hidden('currency_position', currencyPosition(), ['id' => 'currency_position']) }} --}}
    <script>
     function createLetter(event) {
    event.preventDefault(); // Prevent default anchor behavior
    window.location.href = "{{ route('forms.letter.form.create') }}";
    
            // location.reload();
   
}
    </script>
@endsection
