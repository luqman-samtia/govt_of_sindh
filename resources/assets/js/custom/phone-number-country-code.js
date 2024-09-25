document.addEventListener("turbo:load", loadPhoneNumberCountryCode);

function loadPhoneNumberCountryCode() {
    if (!$("#phoneNumber").length) {
        return;
    }

    let input = document.querySelector("#phoneNumber"),
        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");

    let errorMap = [
        Lang.get("messages.placeholder.invalid_number"),
        Lang.get("messages.placeholder.invalid_country_number"),
        Lang.get("messages.placeholder.too_short"),
        Lang.get("messages.placeholder.too_long"),
        Lang.get("messages.placeholder.invalid_number"),
    ];

    // initialise plugin
    let intl = window.intlTelInput(input, {
        initialCountry: "in",
        separateDialCode: true,
        preferredCountries: false,
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(
                function (resp) {
                    var countryCode = resp && resp.country ? resp.country : "";
                    success(countryCode);
                }
            );
        },
        utilsScript: "../../public/assets/js/inttel/js/utils.min.js",
    });

    let reset = function () {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };

    input.addEventListener("blur", function () {
        reset();
        if (input.value.trim()) {
            if (intl.isValidNumber()) {
                validMsg.classList.remove("hide");
            } else {
                input.classList.add("error");
                var errorCode = intl.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.remove("hide");
            }
        }
    });

    // on keyup / change flag: reset
    input.addEventListener("change", reset);
    input.addEventListener("keyup", reset);

    if (typeof phoneNo != "undefined" && phoneNo !== "") {
        setTimeout(function () {
            $("#phoneNumber").trigger("change");
        }, 500);
    }

    $("#phoneNumber").on("blur keyup change countrychange", function () {
        if (typeof phoneNo != "undefined" && phoneNo !== "") {
            intl.setNumber("+" + phoneNo);
            phoneNo = "";
        }
        let getCode = intl.selectedCountryData["dialCode"];
        $("#prefix_code").val(getCode);
    });

    if ($("#isEdit").val()) {
        let getCode = intl.selectedCountryData["dialCode"];
        $("#prefix_code").val(getCode);
    }

    let getPhoneNumber = $("#phoneNumber").val();
    let removeSpacePhoneNumber = getPhoneNumber.replace(/\s/g, "");
    $("#phoneNumber").val(removeSpacePhoneNumber);
}

listen("submit", "#userCreateForm", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#userEditForm", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#kt_account_profile_details_form", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#createSetting", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#newUserSettingForm", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#userSetting", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

listen("submit", "#userProfileEditForm", function (e) {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});
