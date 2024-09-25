<div class="card">
    <div class="card-body">
        <?php
        $style = 'style=';
        $background = 'background-image:';
        ?>
        <div class="mb-3" io-image-input="true">
            <label for="exampleInputImage" class="form-label">{{__('messages.landing_cms.card_one_image')}}: </label>
            <div class="d-block">
                <div class="image-picker">
                    <div class="image previewImage" id="exampleInputImage"
                    {{$style}}"{{$background}}
                    url({{ isset($sectionThree['img_url']) ? asset($sectionThree['img_url']) : asset('web_front/images/main-banner/banner-one/woman-doctor.png')}}
                    ">
                </div>
                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                      title="{{ __('messages.common.edit') }}">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                    <input type="file" id="img_url" name="img_url" class="image-upload d-none"
                                           accept="image/*"/>
                            </label>
                        </span>
            </div>
        </div>
        <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
    </div>

    <div class="col-sm-8">
        <!-- Text Main Field -->
        <div class="form-group col-sm-12 mb-5">
            {{ Form::label('text_main', __('messages.landing_cms.text_main').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('text_main', $sectionThree['text_main'], ['class' => 'form-control form-control-solid','maxLength' => '50','placeholder' => __('messages.landing_cms.text_main')]) }}
        </div>

        <!-- Text Secondary Field -->
        <div class="form-group col-sm-12 mb-5">
            {{ Form::label('text_secondary', __('messages.landing_cms.text_secondary').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('text_secondary', $sectionThree['text_secondary'], ['class' => 'form-control form-control-solid', 'required','maxLength' => '160','placeholder' => __('messages.landing_cms.text_secondary')]) }}
        </div>
    </div>

    <div class="row">
        <!-- Card One Text Field -->
        <div class="form-group col-sm-12 mb-5">
            {{ Form::label('text_one', __('messages.landing_cms.text_one').':', ['class' => 'form-label required mb-3']) }}
                {{ Form::text('text_one', $sectionThree['text_one'], ['class' => 'form-control form-control-solid','maxLength' => '50','placeholder' => __('messages.landing_cms.text_one')]) }}
            </div>

            <!-- Card One Text Secondary Field -->
            <div class="form-group col-sm-12 mb-5">
                {{ Form::label('text_one_secondary', __('messages.landing_cms.card_one_text_secondary').':', ['class' => 'form-label required mb-3']) }}
                {{ Form::text('text_one_secondary', $sectionThree['text_one_secondary'], ['class' => 'form-control form-control-solid','placeholder' => __('messages.landing_cms.card_one_text_secondary')]) }}
            </div>
        </div>

        <div class="row">
            <!-- Card Two Text Field -->
            <div class="form-group col-sm-12 mb-5">
                {{ Form::label('text_two', __('messages.landing_cms.text_two').':', ['class' => 'form-label required mb-3']) }}
                {{ Form::text('text_two', $sectionThree['text_two'], ['class' => 'form-control form-control-solid','maxLength' => '50','placeholder' => __('messages.landing_cms.text_two')]) }}
            </div>

            <!-- Card Two Text Secondary Field -->
            <div class="form-group col-sm-12 mb-5">
                {{ Form::label('text_two_secondary', __('messages.landing_cms.card_two_text_secondary').':', ['class' => 'form-label required mb-3']) }}
                {{ Form::text('text_two_secondary', $sectionThree['text_two_secondary'], ['class' => 'form-control form-control-solid','placeholder' => __('messages.landing_cms.card_two_text_secondary')]) }}
            </div>
        </div>

        <div class="row">
            <!-- Card Three Text Field -->
            <div class="form-group col-sm-12 mb-5">
                {{ Form::label('text_three', __('messages.landing_cms.text_three').':', ['class' => 'form-label mb-3']) }}
                {{ Form::text('text_three', $sectionThree['text_three'], ['class' => 'form-control form-control-solid','maxLength' => '50','placeholder' =>__('messages.landing_cms.text_three')]) }}
            </div>

            <!-- Card Three Text Secondary Field -->
            <div class="form-group col-sm-12 mb-5">
                {{ Form::label('text_three_secondary', __('messages.landing_cms.card_three_text_secondary').':', ['class' => 'form-label required mb-3']) }}
                {{ Form::text('text_three_secondary', $sectionThree['text_three_secondary'], ['class' => 'form-control form-control-solid','placeholder' =>__('messages.landing_cms.card_three_text_secondary')]) }}
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
