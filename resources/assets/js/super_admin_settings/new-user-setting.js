document.addEventListener("turbo:load", loadNewUserSettings);

function loadNewUserSettings() {
    initializeDefaultNewUserCountryCode();
    loadNewUserPhoneNumberCountryCode();
}

function initializeDefaultNewUserCountryCode() {
    if (!$("#newUserCountryPhone").length) {
        return false;
    }

    let input = document.querySelector("#newUserCountryPhone");
    let intl = window.intlTelInput(input, {
        initialCountry: $("#newUserCountryCode").val(),
        separateDialCode: true,
        preferredCountries: false,
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(
                function (resp) {
                    var newUserCountryCode =
                        resp && resp.country ? resp.country : "";
                    success(newUserCountryCode);
                }
            );
        },
        utilsScript: "../../public/assets/js/inttel/js/utils.min.js",
    });

    let getCode =
        intl.selectedCountryData["name"] +
        "+" +
        intl.selectedCountryData["dialCode"];
    $("#newUserCountryPhone").val(getCode);
}

listenClick(".country-code .iti__standard", function () {
    $("#newUserCountryPhone").val($(this).text());
    $(this).attr("data-country-code");
    $("#newUserCountryCode").val($(this).attr("data-country-code"));
});

function loadNewUserPhoneNumberCountryCode() {
    if (!$("#newUserPhoneNumber").length) {
        return;
    }

    let input2 = document.querySelector("#newUserPhoneNumber"),
        errorMsg2 = document.querySelector("#error-msg"),
        validMsg2 = document.querySelector("#valid-msg");

    let errorMap = [
        Lang.get("messages.placeholder.invalid_number"),
        Lang.get("messages.placeholder.invalid_country_number"),
        Lang.get("messages.placeholder.too_short"),
        Lang.get("messages.placeholder.too_long"),
        Lang.get("messages.placeholder.invalid_number"),
    ];

    // initialise plugin
    let intl2 = window.intlTelInput(input2, {
        initialCountry: "in",
        separateDialCode: true,
        preferredCountries: false,
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(
                function (resp) {
                    var newUserCountryCode =
                        resp && resp.country ? resp.country : "";
                    success(newUserCountryCode);
                }
            );
        },
        utilsScript: "../../public/assets/js/inttel/js/utils.min.js",
    });

    let reset2 = function () {
        input2.classList.remove("error");
        errorMsg2.innerHTML = "";
        errorMsg2.classList.add("hide");
        validMsg2.classList.add("hide");
    };

    input2.addEventListener("blur", function () {
        reset2();
        if (input2.value.trim()) {
            if (intl2.isValidNumber()) {
                validMsg2.classList.remove("hide");
            } else {
                input2.classList.add("error");
                var errorCode2 = intl2.getValidationError();
                errorMsg2.innerHTML = errorMap[errorCode2];
                errorMsg2.classList.remove("hide");
            }
        }
    });

    // on keyup / change flag: reset
    input2.addEventListener("change", reset2);
    input2.addEventListener("keyup", reset2);

    if (typeof phoneNo != "undefined" && phoneNo !== "") {
        setTimeout(function () {
            $("#newUserPhoneNumber").trigger("change");
        }, 500);
    }

    $("#newUserPhoneNumber").on("blur keyup change countrychange", function () {
        if (typeof phoneNo != "undefined" && phoneNo !== "") {
            intl2.setNumber("+" + phoneNo);
            phoneNo = "";
        }
        let getCode = intl2.selectedCountryData["dialCode"];
        $("#newUserPrefixCode").val(getCode);
    });

    if ($("#isEdit").val()) {
        let getCode = intl2.selectedCountryData["dialCode"];
        $("#newUserPrefixCode").val(getCode);
    }

    let getPhoneNumber = $("#newUserPhoneNumber").val();
    let removeSpacePhoneNumber = getPhoneNumber.replace(/\s/g, "");
    $("#newUserPhoneNumber").val(removeSpacePhoneNumber);
}

listen("submit", "#newUserSettingForm", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#newUserPhoneNumber").focus();
        return false;
    }
});
