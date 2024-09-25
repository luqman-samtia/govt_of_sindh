<div id="editTestimonialModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.testimonial.edit_testimonial') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'editTestimonialForm','files' => true]) }}
            @method('put')
            @csrf
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    {{ Form::hidden('id',null,['id'=>'testimonialId']) }}
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('name', __('messages.testimonial.name').(':'), ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id' => 'editName','required','placeholder' => __('messages.testimonial.name')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('designation', __('messages.testimonial.designation').(':'), ['class' => 'form-label required mb-3']) }}
                        {{ Form::text('position', null, ['class' => 'form-control form-control-solid','id' => 'editPosition','required','placeholder' => __('messages.testimonial.designation')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('description', __('messages.testimonial.description').(':'),['class' => 'form-label required mb-3']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control form-control-solid description','id' => 'editDescription','rows' => 6,'placeholder' => __('messages.testimonial.description')]) }}
                    </div>
                    <?php
                    $style = 'style=';
                    $background = 'background-image:';
                    ?>
                    <div class="mb-3" io-image-input="true">
                        <label for="exampleInputImage" class="form-label">{{__('messages.profile')}}:</label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="editPreviewImage"
                                {{$style}}"{{$background}} url({{ asset('assets/images/avatar.png') }})">
                            </div>
                            <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                  title="{{ __('messages.user.change_profile') }}">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                    <input type="file" id="profile_image" name="profile" class="image-upload d-none"
                                           accept="image/*"/>
                            </label>
                          </span>
                        </div>
                    </div>
                    <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
                </div>
            </div>
        </div>
        <div class="modal-footer pt-0">
            {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'btnSave','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
            <button type="button" class="btn btn-secondary btn-active-light-primary my-0 ms-3 me-0"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
</div>
