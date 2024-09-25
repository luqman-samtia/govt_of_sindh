document.addEventListener("turbo:load", loadSettings);

function loadSettings() {
    initializeSelect2Dropdown();
    initializeDefaultCountryCode();
}

function initializeDefaultCountryCode() {
    if (!$("#countryPhone").length) {
        return false;
    }

    let input = document.querySelector("#countryPhone");
    let intl = window.intlTelInput(input, {
        initialCountry: $("#countryCode").val(),
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

    let getCode =
        intl.selectedCountryData["name"] +
        "+" +
        intl.selectedCountryData["dialCode"];
    $("#countryPhone").val(getCode);
}

listenClick(".user-country-code .iti__standard", function () {
    console.log($(this).text());
    $("#countryPhone").val($(this).text());
    $(this).attr("data-country-code");
    $("#countryCode").val($(this).attr("data-country-code"));
});

function initializeSelect2Dropdown() {
    let currencyType = $("#currencyType");
    if (!currencyType.length) {
        return false;
    }

    ["#currencyType", "#timeZone", "#dateFormat"].forEach(function (value) {
        if ($(value).hasClass("select2-hidden-accessible")) {
            $(".select2-container").remove();
        }
    });

    $("#currencyType, #timeZone, #dateFormat").select2({
        width: "100%",
    });
}

listenChange("input[type=radio][name=decimal_separator]", function () {
    if (this.value === ",") {
        $('input[type=radio][name=thousand_separator][value="."]').prop(
            "checked",
            true
        );
    } else {
        $('input[type=radio][name=thousand_separator][value=","]').prop(
            "checked",
            true
        );
    }
});

listenChange("input[type=radio][name=thousand_separator]", function () {
    if (this.value === ",") {
        $('input[type=radio][name=decimal_separator][value="."]').prop(
            "checked",
            true
        );
    } else {
        $('input[type=radio][name=decimal_separator][value=","]').prop(
            "checked",
            true
        );
    }
});

listenChange("#appLogo", function () {
    $("#validationErrorsBox").addClass("d-none");
    if (isValidLogo($(this), "#validationErrorsBox")) {
        displaySettingImage(this, "#previewImage");
    }
});

listenChange("#companyLogo", function () {
    $("#validationErrorsBox").addClass("d-none");
    if (isValidLogo($(this), "#validationErrorsBox")) {
        displaySettingImage(this, "#previewImage1");
    }
});

function isValidLogo(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["jpg", "png", "jpeg"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector).removeClass("d-none");
        $(validationMessageSelector)
            .html("The image must be a file of type: jpg, jpeg, png.")
            .show();
        return false;
    }
    $(validationMessageSelector).hide();
    return true;
}

function displaySettingImage(input, selector) {
    let displayPreview = true;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                $(selector).attr("src", e.target.result);
                displayPreview = true;
            };
        };
        if (displayPreview) {
            reader.readAsDataURL(input.files[0]);
            $(selector).show();
        }
    }
}

listenSubmit("#createSetting", function () {
    let companyAddress = $("#companyAddress").val();
    let companyName = $("#company_name").val();
    let appName = $("#app_name").val();
    if (!$.trim(appName)) {
        displayErrorMessage("App Name is required");
        return false;
    }
    if (!$.trim(companyName)) {
        displayErrorMessage("Company Name is required");
        return false;
    }
    if (!$.trim(companyAddress)) {
        displayErrorMessage("Please enter company address");
        return false;
    }
});

listenSubmit("#userSetting", function () {
    let companyName = $("#company_name").val();
    let appName = $("#app_name").val();
    if (!$.trim(appName)) {
        displayErrorMessage("App Name is required");
        return false;
    }
    if (!$.trim(companyName)) {
        displayErrorMessage("Company Name is required");
        return false;
    }
});
