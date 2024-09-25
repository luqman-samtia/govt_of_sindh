@extends('layouts.app')
@section('title')
    {{__('messages.subscription_plans.add_subscription_plan')}}
@endsection
@section('content')
    @php $styleCss = 'style'; @endphp
   <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <h1>@yield('title')</h1>
                    <a class="btn btn-outline-primary float-end"
                       href="{{ url()->previous() }}">{{ __('messages.common.back') }}</a>
                </div>

                <div class="col-12">
                    @include('layouts.errors')
                </div>
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => 'subscription-plans.store','files' => 'true','id'=>'subscriptionPlansCreateForm']) }}
                        @include('subscription_plans.fields')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





