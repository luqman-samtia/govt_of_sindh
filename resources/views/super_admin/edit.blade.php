@extends('layouts.app')
@section('title')
    {{__('messages.super_admin.edit_super_admin')}}
@endsection
@section('content')
 @php $styleCss = 'style'; @endphp
<div class="container-fluid">
    <div class="d-flex flex-column">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <h1>@yield('title')</h1>
                <a class="btn btn-outline-primary float-end"
                   href="{{ url()->previous() }}">{{ __('messages.common.back') }}</a>
            </div>
            <div class="col-12">
                @include('layouts.errors')
            </div>
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['route' => ['super-admins.update', $superAdmin->id], 'method' => 'put',
                            'files' => 'true','id'=>'superAdminEditForm']) !!}
                    @include('super_admin.edit_fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
    {{ Form::hidden('is_edit', true ,['id' => 'isEdit']) }}
    {{ Form::hidden('default_image_url', asset('assets/images/avatar.png') ,['id' => 'defaultImageUrl']) }}
@endsection
@section('phone_js')
    <script>
         phoneNo = "{{ !empty($superAdmin) ? (($superAdmin->region_code).($superAdmin->contact)) : null }}";
    </script>
@endsection
