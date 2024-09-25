@extends('layouts.app')
@section('title')
    {{ __('messages.faqs.faqs') }}
@endsection
@section('content')
  <div class="container-fluid">
        <div class="d-flex flex-column  ">
            @include('flash::message')
            <livewire:faq-table/>
        </div>
    </div>
    @include('landing.faqs.create-modal')
    @include('landing.faqs.edit-modal')
    @include('landing.faqs.show')
@endsection
