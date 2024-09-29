<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $settingValue = getSuperAdminSettingValue();
    @endphp
    @role('super_admin')
    <title>ACE Sindh</title>

    <link rel="icon" href="{{ asset($settingValue['favicon_icon']['value']) }}" type="image/png">
    @else
        <title>@yield('title') | ACE Sindh</title>
        <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
        @endrole
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({ selector:'textarea' });</script> --}}

        <!-- General CSS Files -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
 <!-- Include jQuery -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

 <!-- Include Toastr JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/page.css') }}">
        @if(!Auth::user()->dark_mode)
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins.css') }}">
        @else
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.dark.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins.dark.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/phone-number-dark.css') }}">
            <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

        @endif
        @livewireStyles
        @livewireScripts
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
                data-turbolinks-eval="false" data-turbo-eval="false"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="https://checkout.razorpay.com/v1/checkout.js" data-turbolinks-eval="false"
                data-turbo-eval="false"></script>
        <script src="{{ asset('assets/js/third-party.js') }}"></script>
        <script src="{{ asset('messages.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script data-turbo-eval="false">
            let defaultCountryCodeValue = "{{ getDefaultCountryFromSetting(getLogInUser()->tenant_id) }}"
            let decimalsSeparator = "{{ getSettingValue('decimal_separator') }}"
            let thousandsSeparator = "{{ getSettingValue('thousand_separator') }}"
            let changePasswordUrl = "{{ route('user.changePassword') }}"
            let currentDateFormat = "{{ currentDateFormat() }}"
            let momentDateFormat = "{{ momentJsCurrentDateFormat() }}"
            let ajaxCallIsRunning = false
            var phoneNo = ''
            let makePaymentURL = "{{ route('purchase-subscription') }}"
            let subscribeText = "{{ __('choose plan') }}"
            @if(getSuperAdminStripeKey())
            let stripe = Stripe('{{ getSuperAdminStripeKey() }}')
            @endif
            let subscriptionPlans = "{{ route('subscription.pricing.plans.index') }}"
            let toastData = JSON.parse('@json(session('toast-data'))')
            let makeRazorpayURl = "{{ route('admin.razorpay.init') }}"
            let razorpayPaymentFailed = "{{ route('admin.razorpay.failed') }} "
            let cashPaymentUrl = "{{ route('subscription.cash-payment') }}"
            let razorpayPaymentFailedModal = "{{ route('admin.razorpay.failed.modal') }}"
            let sweetAlertIcon = "{{ asset('assets/images/remove.png') }}"
            let getUserLanguages = "{{getCurrentLanguageName()}}"
            let selectPaymentTypeLang = "{{ __('messages.payment.select_payment_type') }}"
            let selectPaymentModeLang = "{{ __('messages.payment.select_payment_mode') }}"
            Lang.setLocale(getUserLanguages)
            let options = {
                'key': "{{ getSuperAdminRazorpayKey() }}",
                'amount': 1, //  100 refers to 1
                'currency': 'INR',
                'name': "{{getAppName()}}",
                'order_id': '',
                'description': '',
                'image': '{{ getLogoUrl() }}', // logo here
                'callback_url': "{{ route('admin.razorpay.success') }}",
                'prefill': {
                    'email': '', // recipient email here
                    'name': '', // recipient name here
                },
                'readonly': {
                    'name': 'true',
                    'email': 'true',
                },
                'modal': {
                    'ondismiss': function () {
                        $.ajax({
                            type: 'POST',
                            url: razorpayPaymentFailedModal,
                            success: function (result) {
                                if (result.url) {
                                    window.location.href = result.url
                                }
                            },
                            error: function (result) {
                                displayErrorMessage(result.responseJSON.message)
                            },
                        })
                    },
                },
            }
        </script>
        @routes
        <script src="{{ mix('assets/js/pages.js') }}"></script>
        @yield('phone_js')
        <style>
            #dropdown-toggle:after{
                display: none;
            }
            #dropdown-menu{
                min-width: 6rem !important;
                font-size: 3rem;
                padding: 2px;
            }
            .toast-success {
            background-color: #48B7A3 !important;
            color: white !important;
        }
            .toast-message {
            background-color: #48B7A3 !important;
            color: white !important;
        }
            .toast-close-button {
            /* background-color: #48B7A3 !important; */
            color: white !important;
        }
        .toast-error {
            background-color: #dc3545 !important;
            color: white !important;
        }
        .toast {
        /* background-color: #4caf50; Change this to your desired color */
        color: white !important; /* Change text color if needed */
    }
        .form-control:focus{
            overflow: hidden !important;
        }
        #draftsTable div.loading {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    color: #555;
}
body{

    background: linear-gradient(to right, #ffffff 8%, #48b7a3 100%);


}
#gos_bg_color{
    background-color: #48B7A3 !important;
    border:none;
}
.fw-light{
    height: 21px;
}
.btn-primary{
    background: #48B7A3;
    border: none;
}
.btn-primary:hover{
    background: #48B7A3;
    border: none;
}
        </style>
</head>
<body class="main-body">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid" >
        @include('layouts.sidebar')
        <div class="wrapper d-flex flex-column flex-row-fluid custom-overflow-x-hidden" >
            <div class='container-fluid d-flex align-items-stretch justify-content-between px-0' style="background: #48B7A3;">
                @include('layouts.header')
            </div>
            <div class='content d-flex flex-column flex-column-fluid pt-7'>
                @yield('header_toolbar')
                <div class='d-flex flex-wrap flex-column-fluid'>
                    @yield('content')
                </div>
            </div>
            <div class='container-fluid'>
                @include('layouts.footer')
            </div>
        </div>
    </div>
</div>
@include('profile.changePassword')
@include('profile.changeLanguage')
<script>
    $(document).ready(function() {
    $('[autofocus]').removeAttr('autofocus');


    $('#togglePassword').on('click', function() {
        // Get the password input and icon elements
        let passwordInput = $('#password');
        let icon = $('#toggleIcon');

        // Check if the password input type is "password"
        if (passwordInput.attr('type') === 'password') {
            // Change the input type to text, showing the password
            passwordInput.attr('type', 'text');
            // Change the icon to an open eye (assuming you have a bi-eye-fill icon)
            icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        } else {
            // Change the input type back to password, hiding the password
            passwordInput.attr('type', 'password');
            // Change the icon back to a closed eye (eye slash icon)
            icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        }
    });
});

document.querySelectorAll('[autofocus]').forEach(function(element) {
    element.removeAttribute('autofocus');
});
$('input').blur();






</script>
</body>
</html>
