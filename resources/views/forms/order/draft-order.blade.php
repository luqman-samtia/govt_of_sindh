@extends('layouts.app')
@section('title')
    {{-- {{ __('messages.dashboard') }} --}}
@endsection
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <div class="container-fluid">
        <div class="d-flex flex-column">
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
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        {{-- Clients Widget --}}
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
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
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="{{route('total_order')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ count($users_order) }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Orders') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ $total_drafts + count($users_draft_order)}}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Drafts') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
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

                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 mb-5 mb-xl-0">
                    <div class="card">
                        <div style="display: flex;justify-content:space-between;">
                            <div class="card-header pb-0 px-10">
                                <h3 class="mb-0">{{ __('Total Draft Orders') }}</h3>
                            </div>
                            <div class="card-header pb-0 px-10">
                                <a href="{{route('total_draft_order')}}" class="btn btn-danger mb-0">{{ __('Draft Orders') }}-{{count($draft_order)}}</a>

                                <a style="margin-left:5px;" href="{{route('total_draft_letter')}}" class="btn btn-danger mb-0">{{ __('Draft Letters') }}-{{$total_drafts}}</a>
                            </div>
                        </div>

                        <div class="card-body pt-7">

                            {{-- start search and filter --}}
                            <div class="d-sm-flex justify-content-between align-items-center mb-sm-7 mb-4">
                                <div class="d-sm-flex">
                                          <div class="mb-3 mb-sm-0">
                                            <form id="searchForm" class="d-flex position-relative align-items-center">
                                                @csrf
                                                <div class="position-relative d-flex width-220">
                                                    <span class="position-absolute d-flex align-items-center top-0 bottom-0 left-0 text-gray-600 ms-3">
                                                        <svg class="svg-inline--fa fa-magnifying-glass" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"></path></svg>
                                                    </span>
                                                    <input id="searchInput" name="query" class="form-control ps-8" type="search" placeholder="Search By Letter No" aria-label="Search">
                                                </div>
                                                {{-- <div class="position-relative d-flex ms-3 width-220">
                                                    <input id="designationInput" name="designation" class="form-control ps-8" type="search" placeholder="Search by Designation" aria-label="Search">
                                                </div>

                                                <!-- District Filter -->
                                                <div class="position-relative d-flex ms-3 width-220">
                                                    <input id="districtInput" name="district" class="form-control ps-8" type="search" placeholder="Search by District" aria-label="Search">
                                                </div> --}}
                                            </form>

                                        </div>
                                </div>
                          </div>
                {{-- End Search and filter  --}}


                            <div class="table-responsive mt-5" id="lettersTable">
                                @include('forms.order.draft_order_search', ['letters' => $letters])

                             </div>
                            <div id="payment-overview-container" class="justify-align-center">
                                <canvas id="payment_overview"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
    {{-- {{ Form::hidden('currency', getCurrencySymbol(), ['id' => 'currency']) }} --}}
    {{-- {{ Form::hidden('currency_position', currencyPosition(), ['id' => 'currency_position']) }} --}}
@endsection
