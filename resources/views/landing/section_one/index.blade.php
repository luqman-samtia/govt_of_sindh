@extends('layouts.app')
@section('title')
    {{ __('messages.landing_cms.section_one') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors')
            {{ Form::open(['route' => ['super.admin.section.one.update'], 'id' => 'sectionOneForm','method' => 'put', 'files' => true]) }}
            @include('landing.section_one.field')
            {{ Form::close() }}
        </div>
    </div>
@endsection
