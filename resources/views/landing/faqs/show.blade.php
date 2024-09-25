<div id="showFaqModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.faqs.show') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label  mb-3">{{__('messages.faqs.question').':'}}</label>
                        <br><span id="showQuestion" class="text-gray-700"></span>
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label mb-3">{{__('messages.faqs.answer').':'}}</label>
                        <br><span id="showAnswer" class="text-gray-700"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
