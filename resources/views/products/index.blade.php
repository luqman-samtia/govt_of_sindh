@extends('layouts.app')
@section('title')
    {{__('messages.products')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:product-table/>
        </div>
    </div>
    {{ Form::hidden('currency',  getCurrencySymbol(),['id' => 'currency']) }}
@endsection
