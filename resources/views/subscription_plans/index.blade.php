@extends('layouts.app')
@section('title')
    {{__('messages.subscription_plan')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:subscription-plan-table/>
        </div>
    </div>
    {{ Form::hidden('subscription_plan_url',  route('subscription-plans.index'),['id' => 'subscriptionPlanUrl']) }}
    {{ Form::hidden('subscription_plan_url_default', url('subscription-plans'),['id' => 'subscriptionPlanUrlDefault']) }}
    {{ Form::hidden('subscription_plan_url_default_show', url('super-admin/subscription-plans'),['id' => 'subscriptionPlanUrlDefaultShow']) }}
@endsection




