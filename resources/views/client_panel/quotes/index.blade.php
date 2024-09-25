@extends('client_panel.layouts.app')
@section('title')
    Quotes
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:client-quote-table/>
        </div>
    </div>
@endsection
