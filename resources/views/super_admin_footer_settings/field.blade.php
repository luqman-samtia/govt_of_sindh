{{--<div class="alert alert-danger display-none" id="customValidationErrorsBox"></div>--}}
<div class="card">
    <div class="card-body">
<div class="row gx-10 my-5">
    <div class="col-md-12">
        <div class="form-group mb-5">
            {{ Form::label('footer_text', __('messages.footer_setting.footer_text').':', ['class' => 'form-label mb-3']) }}
            <span class="required"></span>
            {{ Form::textarea('footer_text', $settings['footer_text'], ['class' => 'form-control form-control-solid', 'required', 'id' => 'footerText','tabindex' => '1','rows'=> '5','maxLength'=> 270,'placeholder' => __('messages.footer_setting.footer_text')]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label mb-3']) }}
            <span class="required"></span>
            {{ Form::email('email', $settings['email'], ['class' => 'form-control form-control-solid', 'required','placeholder' => __('messages.user.email')]) }}
        </div>
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('phone',__('messages.footer_setting.phone_number').':',['class' => 'form-label required mb-3']) }}
        {!! Form::tel('phone', $settings['phone'], ['class' => 'form-control form-control-solid', 'required','id' => 'phoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','placeholder' => __('messages.footer_setting.phone_number')]) !!}
        {!! Form::hidden('region_code', $settings['region_code'] ?? null,['id'=>'prefix_code']) !!}
        <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓ &nbsp; {{ __('messages.placeholder.valid_number') }}</span>
        <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
    </div>
    <!-- Facebook URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('facebook_url', __('messages.footer_setting.facebook_url').':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('facebook_url', $settings['facebook_url'], ['class' => 'form-control form-control-solid','id'=>'facebookUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder' => __('messages.footer_setting.facebook_url')]) }}
    </div>

    <!-- Twitter URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('twitter_url', __('messages.footer_setting.twitter_url').':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('twitter_url', $settings['twitter_url'], ['class' => 'form-control form-control-solid','id'=>'twitterUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder' => __('messages.footer_setting.twitter_url')]) }}
    </div>

    <!-- Youtube URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('youtube_url', __('messages.footer_setting.youtube_url').':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('youtube_url', $settings['youtube_url'], ['class' => 'form-control form-control-solid', 'id'=>'youtubeUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder' => __('messages.footer_setting.youtube_url')]) }}
    </div>

    <!-- LinkedIn URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('linkedin_url', __('messages.footer_setting.linkedIn_url').':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('linkedin_url', $settings['linkedin_url'], ['class' => 'form-control form-control-solid','id'=>'linkedInUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder' => __('messages.footer_setting.linkedIn_url')]) }}
    </div>
    <div class="col-md-12">
        <div class="form-group mb-5">
            {{ Form::label('address', __('messages.footer_setting.address').':', ['class' => 'form-label mb-3']) }}
            <span class="required"></span>
            {{ Form::textarea('address', $settings['address'], ['class' => 'form-control form-control-solid','maxLength'=> 60,'placeholder' => __('messages.footer_setting.address'), 'rows'=> '5','required']) }}
        </div>
    </div>
</div>
<div class="float-end d-flex">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2','id' => 'btnSave']) }}
    {{ Form::reset(__('messages.common.cancel'), ['class' => 'btn btn-secondary btn-active-light-primary me-2']) }}
</div>
    </div>
</div>
