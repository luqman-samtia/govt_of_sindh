<div class="modal fade" tabindex="-1" id="addFaqModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{__('messages.faqs.add_faq')}}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open(['id'=>'addFaqForm','files' => true]) }}
                <div class="modal-body scroll-y">
                    <div class="alert alert-danger display-none hide" id="validationErrorsBox"></div>
                    <div class="row">
                        <input type="hidden" id="faqsId">
                        <div class="form-group col-sm-12 mb-5">
                            {{ Form::label('question', __('messages.faqs.question').(':'), ['class' => 'form-label required mb-3']) }}
                            {{ Form::text('question', null, ['class' => 'form-control form-control-solid','required','placeholder' => __('messages.faqs.question')]) }}
                        </div>
                        <div class="form-group col-sm-12 mb-5">
                            {{ Form::label('answer', __('messages.faqs.answer').(':'), ['class' => 'form-label required mb-3']) }}
                            {{ Form::textarea('answer', null, ['class' => 'form-control form-control-solid','required', 'rows' => 6,'placeholder' => __('messages.faqs.answer')]) }}
                        </div>
                    </div>
                </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'faqSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary my-0 ms-3 me-0"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
                {{ Form::close() }}
        </div>
    </div>
</div>
