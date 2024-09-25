@extends('layouts.app')
@section('title')
    Currency
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:admin-currency-table/>
        </div>
    </div>
 @include('super_admin.currencies.create_modal')
@include('super_admin.currencies.edit_modal')
@endsection
