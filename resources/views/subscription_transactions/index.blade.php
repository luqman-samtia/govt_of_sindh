@extends('layouts.app')
@section('title')
    {{ __('messages.subscription_plans.transactions') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:subscription-transaction-table/>
        </div>
    </div>
    {{ Form::hidden('subscription_transaction_url', null ,['id' => 'subscriptionTransactionUrl']) }}
    {{ Form::hidden('is_super_admin_login', getLoggedInUser()->hasRole('Super Admin') ,['id' => 'isSuperAdminLogin']) }}
    {{ Form::hidden('paid', \App\Models\Transaction::PAID ,['id' => 'paid']) }}
    {{ Form::hidden('unpaid', \App\Models\Transaction::UNPAID,['id' => 'unpaid']) }}
    {{ Form::hidden('stripe', \App\Models\Subscription::PAYMENT_TYPES[1],['id' => 'stripe']) }}
    {{ Form::hidden('paypal', \App\Models\Subscription::PAYMENT_TYPES[2],['id' => 'paypal']) }}
    @if(getLoggedInUser()->hasRole('Super Admin'))
        {{ Form::hidden('subscription_transaction_url', route('subscriptions.transactions.index'),['id' => 'subscriptionTransactionUrl']) }}
    @else
        {{ Form::hidden('subscription_transaction_url', route('subscriptions.plans.transactions.index'),['id' => 'subscriptionTransactionUrl']) }}
    @endif
@endsection
