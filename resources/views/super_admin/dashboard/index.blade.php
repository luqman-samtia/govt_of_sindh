@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }}
@endsection
@section('content')
<div class="container-fluid">
    @if(session('message'))
    <span class="alert alert-success">
        {{ session('message') }}
    </span>
            @endif
            @if(session('error'))
    <span class="alert alert-danger">
        {{ session('error') }}
    </span>
            @endif
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('users.index') }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user display-4 card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white"> {{ formatTotalAmount($data['users']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.total_users') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{route('super.admin.total.letters')}}"
                        class="mb-xl-8 text-decoration-none">

                        <div
                        class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">

                                <div
                                    class="bg-green-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    {{-- <i class="fas fa-rupee-sign display-4 card-icon text-white"></i> --}}
                                    <i class="fas fa-file-invoice card-icon text-white"></i>
                                </div>
                                {{-- <div class="text-center">

                                    <h2 style="text-align: center;">jjjjjj</h2>
                                </div> --}}
                                <div class="text-end text-white">
                                     {{-- <h3 class="fs-1-xxl text-white text-center">LETTERS</h3> --}}

                                    <h2 class="fs-1-xxl fw-bolder text-white">{{count($users_form)}}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Letters Issued') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href=""
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-toggle-on display-4 card-icon text-white"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">0</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Order Issued') }}</h3>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{route('super.admin.total.draft.letters')}}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-file-invoice card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{count($draft)}}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Drafts') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-4">
                <div class="">
                    <div class="card mt-3">
                        <div class="card-body p-5">
                            <div class="card-header border-0 pt-5">
                                <h3 class="mb-0">{{  __('Show Data Here') }}</h3>
                                <div class="ms-auto">
                                    <div id="rightData" class="date-picker-space">
                                        {{-- <input class="form-control removeFocus" id="super_admin_time_range"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-lg-6 p-0">
                                <div class="">
                                    <div id="revenue_overview-container" class="pt-2">
                                        {{-- <canvas id="revenue_chart_canvas" height="200" width="905"></canvas> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
    {{-- {{ Form::hidden('currency',  getCurrencySymbol(),['id' => 'currency']) }} --}}
    {{-- {{ Form::hidden('currency_position',  superAdminCurrencyPosition(),['id' => 'currency_position']) }} --}}
@endsection
