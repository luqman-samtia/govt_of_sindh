<div id="showTestimonialModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.testimonial.show_testimonial') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label mb-3">{{__('messages.testimonial.name').':'}}</label>
                        <span class="show-name"></span>
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label mb-3">{{__('messages.testimonial.designation').':'}}</label>
                        <span class="show-position"></span>
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label mb-3">{{__('messages.testimonial.description').':'}}</label>
                        <span class="show-description"></span>
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        <label class="form-label mb-3" for="file"> <span>{{__('messages.profile')}}: </span>
                        </label>
                        <?php
                        $style = 'style=';
                        $background = 'background-image:';
                        ?>
                        <br>
                        <div class="mb-3">
                            <div class="d-block">
                                <div class="image image-medium">
                                    <img class="" id="showPreviewImage" src="{{asset('assets/images/avatar.png')}}">
                                </div>
                            </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


