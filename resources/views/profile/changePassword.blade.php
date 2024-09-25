@php $styleCss = 'style'; @endphp
<div class="modal show fade" id="changePasswordModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.user.change_password') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" class="form">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editPasswordValidationErrorsBox"></div>
                    <div class="mb-5">
                        <label class="form-label mb-2 required">{{ __('messages.user.current_password') . ':' }}</label>
                        <div class="mb-3 position-relative">
                            <input class="form-control" type="password"
                                placeholder="{{ __('messages.user.current_password') }}" name="current_password"
                                autocomplete="off" aria-label="Password" data-toggle="password" />
                            <span
                                class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label mb-2 required">{{ __('messages.user.new_password') . ':' }}</label>
                        <div class="mb-3 position-relative">
                            <input class="form-control" type="password"
                                placeholder="{{ __('messages.user.new_password') }}" name="new_password"
                                autocomplete="off" aria-label="Password" data-toggle="password" />
                            <span
                                class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label mb-2 required">{{ __('messages.user.confirm_password') . ':' }}</label>
                        <div class="mb-3 position-relative">
                            <input class="form-control" type="password"
                                placeholder="{{ __('messages.user.confirm_password') }}" name="confirm_password"
                                autocomplete="off" aria-label="Password" data-toggle="password" />
                            <span
                                class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    {{ Form::button(__('messages.common.save'), ['class' => 'btn btn-primary mr-2 me-3', 'id' => 'passwordChangeBtn']) }}
                    {{ Form::button(__('messages.common.cancel'), ['class' => 'btn btn-secondary btn-active-light-primary me-2', 'data-bs-dismiss' => 'modal']) }}
                </div>
            </form>
        </div>
    </div>
</div>
