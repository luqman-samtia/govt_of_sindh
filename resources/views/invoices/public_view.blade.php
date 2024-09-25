<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice | {{ getAppName($invoice->tenant_id) }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset(getSettingValue('favicon_icon', $invoice->tenant_id)) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    @yield('page_css')
    <!-- General CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/page.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins.css') }}">
    @yield('css')
    <script>
        let decimalsSeparator = "{{ getSettingValue('decimal_separator') }}"
        let thousandsSeparator = "{{ getSettingValue('thousand_separator') }}"
        let currentDateFormat = "{{ currentDateFormat() }}"
        let momentDateFormat = "{{ momentJsCurrentDateFormat() }}"
        let ajaxCallIsRunning = false
        let phoneNo = ''
        let selectPaymentTypeLang = "{{ __('messages.payment.select_payment_type') }}"
        let selectPaymentModeLang = "{{ __('messages.payment.select_payment_mode') }}"
        let toastData = JSON.parse('@json(session('toast-data'))')
    </script>
    @routes
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ asset('assets/js/third-party.js') }}"></script>
    <script src="{{ mix('assets/js/pages.js') }}"></script>
    <script src="{{ asset('messages.js') }}"></script>
    <script>
        @if (getStripeKeyForPublic($tenantId))
            let stripe = Stripe('{{ getStripeKeyForPublic($tenantId) }}');
        @endif
        let options = {
            'key': "{{ getRazorpayKeyForPublic($tenantId) }}",
            'amount': 0, //  100 refers to 1
            'currency': 'INR',
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': '{{ asset(getLogoUrl()) }}', // logo here
            'callback_url': "{{ route('public.razorpay.success') }}",
            'prefill': {
                'email': '', // client email here
                'name': '', // client name here
                'invoiceId': '', //invoice id
            },
            'readonly': {
                'name': 'true',
                'email': 'true',
                'invoiceId': 'true',
            },
            'theme': {
                'color': '#4FB281',
            },
            'modal': {
                'ondismiss': function() {
                    $('#publicPaymentModal').modal('hide');
                    displayErrorMessage('Payment not completed.');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
            },
        };
    </script>
</head>

<body>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                        <div class="p-4 p-sm-12">
                            @include('flash::message')
                            @include('invoices.show_fields', ['isPublicView' => false])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('invoices.public_payment_modal')
</body>

</html>
