@extends('layouts.app')
@section('title')
    {{ __('messages.categories') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:category-table/>
        </div>
    </div>
@include('category.create_modal')
@include('category.edit_modal')
@endsection
