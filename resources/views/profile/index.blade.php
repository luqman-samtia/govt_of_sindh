@extends('layouts.app')
@section('title')
    {{ __('messages.user.profile_details') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="col-12">
                @include('layouts.errors')
                @include('flash::message')
                <div class="card">
                    <form id="userProfileEditForm" method="POST"
                          action="{{ route('update.profile.setting') }}"
                          class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('messages.user.avatar').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <div class="d-block">
                                        <div class="image-picker">
                                            <div class="image previewImage" id="exampleInputImage"
                                                 style="background-image: url('{{ !empty(getLogInUser()->profile_image) ? getLogInUser()->profile_image : asset('assets/images/avatar.png') }}')">
                                            </div>
                                            <span class="picker-edit rounded-circle text-gray-500 fs-small"
                                                  data-bs-toggle="tooltip"
                                                  title="{{ __('messages.user.change_profile') }}">
                                            <label>
                                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                                <input type="file" id="profile_image" name="image"
                                                       class="image-upload d-none" accept="image/*"/>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('messages.user.full_name').':' }}</label>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            @if (Auth::user()->hasRole('admin'))
                                            {!! Form::text('first_name', $user->first_name, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.client.first_name'), 'required', 'id' => 'editProfileFirstName']) !!}

                                            @else
                                            {!! Form::text('first_name', $user->first_name, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.client.first_name'), 'readonly', 'id' => 'editProfileFirstName']) !!}

                                            @endif
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            @if (Auth::user()->hasRole('admin'))
                                            {!! Form::text('last_name', $user->last_name, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.client.last_name'), 'required', 'id' => 'editProfileLastName']) !!}
                                            @else

                                            {!! Form::text('last_name', $user->last_name, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.client.last_name'), 'readonly', 'id' => 'editProfileLastName']) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('messages.user.email').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    @if (Auth::user()->hasRole('admin'))
                                    {!! Form::email('email', $user->email, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.user.email'), 'required', 'id' => 'isEmailEditProfile']) !!}

                                    @else
                                    {!! Form::email('email', $user->email, ['class'=> 'form-control removeFocus', 'placeholder' => __('messages.user.email'), 'required', 'id' => 'isEmailEditProfile']) !!}

                                    @endif
                                </div>
                            </div>
                            @hasrole('admin')
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('Designation').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {{-- {!! Form::designation('designation', $user->designation, ['class'=> 'form-control removeFocus', 'placeholder' => __('designation'), 'required', 'id' => 'isDesignationEditProfile']) !!} --}}
                                    {{-- <input type="text" class="form-control removeFocus" value="{{$user->designation}}" > --}}
                                    {!! Form::text('designation', $user->designation, ['class'=> 'form-control', 'required','removeFocus']) !!}

                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('Zone').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {{-- {!! Form::designation('designation', $user->designation, ['class'=> 'form-control removeFocus', 'placeholder' => __('designation'), 'required', 'id' => 'isDesignationEditProfile']) !!} --}}
                                    {{-- <input type="text" class="form-control removeFocus" value="{{$user->designation}}" > --}}
                                    {!! Form::text('zone', $user->zone, ['class'=> 'form-control', 'required']) !!}

                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('District').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {!! Form::text('district', $user->district, ['class'=> 'form-control', 'required']) !!}

                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('Address').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {!! Form::text('address', $user->address, ['class'=> 'form-control','placeholder' => __('Address'), 'required'  , 'id' => 'isEditProfileAddress']) !!}

                                </div>
                            </div>
                            @error('address')
                                <span style="color: red;">{{$message}}</span>
                            @enderror
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('Date').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {!! Form::date('date', $user->date, ['class'=> 'form-control','placeholder' => __('Date'), 'required']) !!}

                                </div>
                            </div>
                            @error('address')
                            <span style="color: red;">{{$message}}</span>
                           @enderror
                           <div class="row mb-6">
                            {{ Form::label('office_number', __('Office Number').':', ['class' => 'form-label col-lg-4']) }}
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {{-- {{ Form::tel('tel', isset($user) ? $user->tel : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('Office No'), 'required']) }} --}}
                                <input type="text" name="tel" min="11" class="form-control form-control-solid" placeholder="Enter Your Office Number" value="{{(isset($user) ? $user->tel : null)}}" id="editProfileTel" required>
                                <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓ &nbsp; {{ __('messages.placeholder.valid_number') }}</span>
                                <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
                            </div>
                        </div>
                        @error('tel')
                            {{$message}}
                        @enderror
                            <div class="row mb-6">
                                <label class="col-lg-4 form-label required">{{ __('Grade').':' }}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {{-- {!! Form::designation('designation', $user->designation, ['class'=> 'form-control removeFocus', 'placeholder' => __('designation'), 'required', 'id' => 'isDesignationEditProfile']) !!} --}}
                                    {{-- <input type="text" class="form-control removeFocus" value="{{$user->designation}}" > --}}
                                    {!! Form::text('grade', $user->grade, ['class'=> 'form-control', 'required']) !!}

                                </div>
                            </div>
                            @endrole
                            <div class="row mb-6">
                                {{ Form::label('contact', __('messages.user.contact_number').':', ['class' => 'form-label col-lg-4']) }}
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    {{-- {{ Form::tel('contact', isset($user)?$user->contact:null, ['class' => 'form-control removeFocus', 'placeholder' =>  __('messages.client.contact_no'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','id'=>'phoneNumber']) }} --}}
                                    {{-- {{ Form::tel('contact', isset($user) ? $user->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'),'readonly']) }} --}}
                                    @if (Auth::user()->hasRole('admin'))
                                        {{ Form::tel('contact', isset($user) ? $user->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'), 'required']) }}
                                    @else
                                        {{ Form::tel('contact', isset($user) ? $user->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'), 'required']) }}
                                    @endif
                                    <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓ &nbsp; {{ __('messages.placeholder.valid_number') }}</span>
                                    <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6">
                                <div class="mb-5">
                                    {{ Form::label('contact', __('messages.client.contact_no').':', ['class' => 'form-label mb-3 required']) }}
                                    {{ Form::tel('contact', isset($user) ? $user->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'),'required']) }}
                                </div>
                            </div> --}}
                            @hasrole('admin')
                            <div class="float-end mb-6">
                                {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
                                <a href="{{route('admin.dashboard')}}" type="reset"
                                   class="btn btn-secondary btn-active-light-primary">{{__('messages.common.cancel')}}</a>
                            </div>
                            @elserole('super_admin')
                            <div class="float-end mb-6">
                                {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
                                <a href="{{route('super.admin.dashboard')}}" type="reset"
                                   class="btn btn-secondary btn-active-light-primary">{{__('messages.common.cancel')}}</a>
                            </div>
                            @else
                            <div class="float-end mb-6">
                                {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
                                <a href="{{route('client.dashboard')}}" type="reset"
                                       class="btn btn-secondary btn-active-light-primary">{{__('messages.common.cancel')}}</a>
                            </div>
                            @endrole
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('phone_js')
    <script>
        phoneNo = "{{ !empty($user) ? (($user->region_code).($user->contact)) : null }}";
    </script>
@endsection
