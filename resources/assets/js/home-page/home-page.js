$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).on("click", ".languageSelection", function (e) {
    e.preventDefault();
    let languageName = $(this).data("prefix-value");
    $.ajax({
        type: "POST",
        url: route("change.language"),
        data: { languageName: languageName },
        success: function () {
            location.reload();
        },
    });
});

// hide cookie consent on click of agree button
$(document).on("click", ".js-cookie-consent-agree", function (e) {
    $(".js-cookie-consent").addClass("d-none");
});

// hide cookie consent on click of decline button
$(document).on("click", ".js-cookie-consent-declined", function (e) {
    $(".js-cookie-consent").addClass("d-none");
    $.ajax({
        type: "POST",
        url: route("declined.cookie"),
        success: function (result) {},
        error: function (result) {},
    });
});
