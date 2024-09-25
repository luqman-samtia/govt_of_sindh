@extends('layouts.app')
@section('title')
    {{ __('messages.enquiries') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:super-admin-enquiry-table/>
        </div>
    </div>
    {{ Form::hidden('enquiry_url',  route('super.admin.enquiry.index'),['id' => 'enquiryUrl']) }}
@endsection
