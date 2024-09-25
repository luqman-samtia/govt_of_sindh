@extends('layouts.app')
@section('title')
    {{__('messages.super_admin.add_super_admin')}}
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
                        {!! Form::open(['route' => 'super-admins.store','files' => 'true','id'=>'superAdminCreateForm']) !!}
                        @include('super_admin.fields')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::hidden('is_edit', false ,['id' => 'isEdit']) }}
@endsection
@section('phone_js')
    <script>
        var phoneNo = "{{ old('region_code').old('contact') }}";
    </script>
@endsection

