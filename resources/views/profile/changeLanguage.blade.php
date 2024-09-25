<div class="modal fade" id="changeLanguageModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3> {{ __('messages.user.change_language') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'changeLanguageForm']) }}
            @csrf
            @method('POST')
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="editLanguageValidationErrorsBox"></div>
                <div>
                    @php
                        $user = Auth::user();
                    @endphp
                    {{ Form::label('Language', __('messages.user.change_language') . ':', ['class' => 'form-label']) }}
                    {{ Form::select('languageName', getUserLanguages(), isset($user) ? getLogInUser()->language : null, ['class' => 'form-control form-select', 'required', 'data-control' => 'select2', 'id' => 'selectLanguage', 'data-dropdown-parent' => '#changeLanguageModal']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['class' => 'btn btn-primary m-0', 'id' => 'languageChangeBtn']) }}
                {{ Form::button(__('messages.common.cancel'), ['class' => 'btn btn-secondary my-0 ms-3 me-0', 'data-bs-dismiss' => 'modal']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
