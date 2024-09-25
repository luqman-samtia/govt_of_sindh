@extends('layouts.app')
@section('title')
    {{ __('messages.landing_cms.section_three') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors')
            {{ Form::open(['route' => ['super.admin.section.three.update'],' method' => 'POST', 'files' => true]) }}
            @method('PUT')
            @include('landing.section_three.field')
            {{ Form::close() }}
        </div>
    </div>
@endsection
