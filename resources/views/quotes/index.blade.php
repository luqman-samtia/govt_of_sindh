@extends('layouts.app')
@section('title')
    {{__('messages.quotes')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:quote-table/>
        </div>
    </div>
    @include('quotes.templates.templates')
    {{ Form::hidden('currency', getCurrencySymbol(),['id' => 'currency']) }}
    {{ Form::hidden('status',  $status,['id' => 'status']) }}
@endsection
