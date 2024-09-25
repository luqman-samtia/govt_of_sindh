'use strict';

$(document).ready(function () {
    $('[data-toggle="password"]').each(function () {
        var input = $(this);
        var eye_btn = $(this).parent().find('.input-icon');
        eye_btn.css('cursor', 'pointer').addClass('input-password-hide');
        eye_btn.on('click', function () {
            if (eye_btn.hasClass('input-password-hide')) {
                eye_btn.removeClass('input-password-hide').addClass('input-password-show');
                eye_btn.find('.bi').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill')
                input.attr('type', 'text');
            } else {
                eye_btn.removeClass('input-password-show').addClass('input-password-hide');
                eye_btn.find('.bi').removeClass('bi-eye-fill').addClass('bi-eye-slash-fill')
                input.attr('type', 'password');
            }
        });
    });
});
