'use strict';

toastr.options = {
    'closeButton': true,
    'debug': false,
    'newestOnTop': false,
    'progressBar': true,
    'positionClass': 'toast-top-right',
    'preventDuplicates': false,
    'onclick': null,
    'showDuration': '300',
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    })
});

$(document).on('submit', '#contactEnquiryForm', function (e){
    e.preventDefault();
    $.ajax({
        url: route('super.admin.enquiry.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                toastr.success(result.message);
                $('#contactEnquiryForm')[0].reset()
            } else {
                toastr.error(result.message);
            }
        },
        error: function (result) {
            toastr.error(result.responseJSON.message);
            $('#contactEnquiryForm')[0].reset()
        },
    })
});

$(document).on('click', '#subscribeBtn', function (e){
    e.preventDefault();

    $.ajax({
        url: route('subscribe.store'),
        type: 'POST',
        data: $('#subscribe-form').serialize(),
        success: function (result) {
            if (result.success) {
                toastr.success(result.message);
                $('#subscribe-form')[0].reset()
            }
        },
        error: function (result) {
            toastr.error(result.responseJSON.message);
            $('#subscribe-form')[0].reset()
        },
    })
});
