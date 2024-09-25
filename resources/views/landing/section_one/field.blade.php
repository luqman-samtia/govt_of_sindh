<div class="card">
    <div class="card-body">
        <div class="row">
            <?php
            $style = 'style=';
            $background = 'background-image:';
            ?>
            <div class="mb-3" io-image-input="true">
                <label for="exampleInputImage" class="form-label">{{__('messages.landing_cms.card_one_image')}}
                    : </label>
                <div class="d-block">
                    <div class="image-picker">
                        <div class="image previewImage" id="exampleInputImage"
                        {{$style}}"{{$background}}
                        url({{ isset($sectionOne['img_url_one']) ? asset($sectionOne['img_url_one']) : asset('landing-page/images/hero-image.png') }}
                        ">
                    </div>
                    <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                          title="{{ __('messages.common.edit') }}">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                    <input type="file" id="img_url_one" name="img_url_one" class="image-upload d-none"
                                           accept="image/*"/>
                            </label>
                        </span>
                </div>
            </div>
            <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
        </div>

        <!-- Text Main Field -->
        <div class="form-group col-sm-12 mb-5">
            {{ Form::label('text_main', __('messages.landing_cms.text_main').':', ['class' => 'form-label required  mb-3']) }}
            {{ Form::text('text_main', $sectionOne['text_main'], ['class' => 'form-control form-control-solid', 'id' => 'textMain','maxLength' => '45','placeholder' => __('messages.landing_cms.text_main')]) }}
        </div>

        <!-- Text Secondary Field -->
        <div class="form-group col-sm-12 mb-5">
            {{ Form::label('text_secondary', __('messages.landing_cms.text_secondary').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('text_secondary', $sectionOne['text_secondary'], ['class' => 'form-control form-control-solid', 'id' => 'textSecondary', 'required','maxLength' => '191','placeholder' => __('messages.landing_cms.text_secondary')]) }}
        </div>

        </div>
        <div class="float-end">
            <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
                {{ Form::reset(__('messages.common.cancel'), ['class' => 'btn btn-secondary btn-active-light-primary']) }}
            </div>
        </div>

    </div>
</div>
