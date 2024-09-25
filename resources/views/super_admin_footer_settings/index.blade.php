@extends('layouts.app')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors')
            {{ Form::open(['route' => 'super.admin.footer.settings.update','method'=>'POST', 'id'=>'superAdminFooterSettingForm']) }}
                @include('super_admin_footer_settings.field')
            {{ Form::close() }}
        </div>
    </div>
    {{ Form::hidden('is_edit',  true,['id' => 'isEdit']) }}
@endsection
@section('phone_js')
    <script>
         phoneNo = "{{ !empty($settings['region_code']) ? (($settings['region_code']).($settings['phone'])) : null }}"
    </script>
@endsection
