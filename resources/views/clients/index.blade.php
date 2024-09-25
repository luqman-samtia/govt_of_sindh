@extends('layouts.app')
@section('title')
    {{__('messages.clients')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:client-table/>
        </div>
    </div>
@endsection
