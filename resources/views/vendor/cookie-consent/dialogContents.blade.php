<div
    class="js-cookie-consent cookie-consent bottom-0 position-sticky d-flex align-items-center text-center justify-content-center">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-2 rounded-lg">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 items-center hidden md:inline">
                    <p class="ml-3 cookie-consent__message">
                        {{ getSuperAdminSetting('cookie_warning')->value ?? '' }}
                    </p>
                </div>
                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto d-flex justify-content-center">
                    <button
                        class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center me-3 px-4 py-2 rounded-md text-sm font-medium text-yellow-800 bg-yellow-400 hover:bg-yellow-300">
                        {{ __('messages.common.allow_cookie') }}
                    </button>
                    <button
                        class="d-flex js-cookie-consent-declined btn btn-danger d-flex align-items-center text-center justify-content-center me-2 p-2 ">
                        {{ __('messages.common.declined') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
