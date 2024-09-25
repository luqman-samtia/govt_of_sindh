@extends('layouts.app')
@section('title')
    {{__('messages.super_admins')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:super-admin-table/>
        </div>
    </div>
@endsection
