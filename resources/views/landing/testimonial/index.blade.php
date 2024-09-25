@extends('layouts.app')
@section('title')
    {{ __('messages.testimonials') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column  ">
            @include('flash::message')
            <livewire:admin-testimonial-table/>
        </div>
    </div>
    @include('landing.testimonial.create-modal')
    @include('landing.testimonial.edit-modal')
    @include('landing.testimonial.show')
    {{ Form::hidden('profile_error',__('messages.testimonial.profile_error'),['id' => 'profileError']) }}
    {{ Form::hidden('default_document_image_url',asset('assets/images/avatar.png'),['id' => 'defaultDocumentImageUrl']) }}
@endsection

