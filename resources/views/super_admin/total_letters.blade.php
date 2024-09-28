@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }}


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
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 widget">
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
                    <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 widget">
                        <a href=""
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

                                    <h2 class="fs-1-xxl fw-bolder text-white">{{count($letters)}}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Letters Issued') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 widget">
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
                    <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 widget">
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
                                <h3 class="mb-0">{{  __('Total Letters') }}</h3>
                                <div class="ms-auto">
                                    <div id="rightData" class="date-picker-space">
                                        {{-- <input class="form-control removeFocus" id="super_admin_time_range"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-lg-6 p-0">


                                 {{-- table --}}

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
                                                        <input id="searchInput" name="query" class="form-control ps-8" type="search" placeholder="Letter No,U-Name," aria-label="Search">
                                                    </div>
                                                    <div class="position-relative d-flex ms-3 width-220">
                                                        <input id="designationInput" name="designation" class="form-control ps-8" type="search" placeholder="Search by Designation" aria-label="Search">
                                                    </div>

                                                    <!-- District Filter -->
                                                    <div class="position-relative d-flex ms-3 width-220">
                                                        <input id="districtInput" name="district" class="form-control ps-8" type="search" placeholder="Search by District" aria-label="Search">
                                                    </div>
                                                </form>

                                            </div>
                                    </div>

                                    {{-- <div class="d-sm-flex d-block justify-content-end">
                            <a type="button" class="btn btn-primary" href="http://public_html.test/super-admin/users/create">
                                Add User
                            </a>
                            <a type="button" class="btn btn-primary mx-5" href="http://public_html.test/super-admin/super-admins/create">
                                Add Super Admin
                            </a>
                            </div> --}}
                    </div>
                    {{-- End Search and filter  --}}
            <div class="table-responsive mt-5"  id="lettersTable" >

                @include('super_admin.render_table_search', ['letters' => $letters])

             </div>

            {{-- end table --}}


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




