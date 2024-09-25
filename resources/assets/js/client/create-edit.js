document.addEventListener("turbo:load", createEditClient);

function createEditClient() {
    $(".search-email-list").addClass("d-none");
    loadSelect2Dropdown();
}

function loadSelect2Dropdown() {
    let countyIdDropdownSelector = $("#countryID");
    if (!countyIdDropdownSelector.length) {
        return false;
    }

    if ($("#countryID").hasClass("select2-hidden-accessible")) {
        $(".select2-container").remove();
    }
    if ($("#stateID").hasClass("select2-hidden-accessible")) {
        $(".select2-container").remove();
    }

    $("#countryID, #stateID").select2({
        width: "100%",
    });
}

listenChange("#countryId", function () {
    $.ajax({
        url: route("states-list"),
        type: "get",
        dataType: "json",
        data: { countryId: $(this).val() },
        success: function (data) {
            $("#stateId").empty();
            $("#cityId").empty();
            $("#stateId").select2({
                placeholder: "Select State",
                allowClear: false,
            });
            $("#cityId").select2({
                placeholder: "Select City",
                allowClear: false,
            });
            $("#stateId").append(
                $('<option value=""></option>').text("Select State")
            );
            $.each(data.data, function (i, v) {
                $("#stateId").append(
                    $("<option></option>").attr("value", i).text(v)
                );
            });

            if ($("#isEdit").val() && $("#stateId").val()) {
                $("#stateId").val($("#stateId").val()).trigger("change");
            }
        },
    });
});

listenChange("#stateId", function () {
    $.ajax({
        url: route("cities-list"),
        type: "get",
        dataType: "json",
        data: {
            stateId: $(this).val(),
            country: $("#countryId").val(),
        },
        success: function (data) {
            $("#cityId").empty();
            $("#cityId").select2({
                placeholder: "Select City",
                allowClear: false,
            });
            $.each(data.data, function (i, v) {
                $("#cityId").append(
                    $("<option></option>").attr("value", i).text(v)
                );
            });

            if ($("#isEdit").val() && $("#cityId").val()) {
                $("#cityId").val($("#cityId").val()).trigger("change");
            }
        },
    });
});

listenClick(".remove-image", function () {
    defaultAvatarImagePreview("#previewImage", 1);
});

listenSubmit("#clientForm, #editClientForm", function () {
    if ($("#error-msg").text() !== "") {
        $("#phoneNumber").focus();
        return false;
    }
});

function setEditCountryId() {
    let isEditForm = $("#isEdit");
    if (!isEditForm.length) {
        return false;
    }
    if ($("#isEdit").val() && $("#countryId").val()) {
        $("#countryId").val($("#countryId").val()).trigger("change");
    }
}

listenKeyup(".search-email", function () {
    let enterEmail = $(this).val().trim();
    let editEmail = $(".edit-time-email").val();

    if ($(this).hasClass("edit-time-search") && editEmail == enterEmail) {
        $(".search-email-list").addClass("d-none");
        $(
            ".user-first-name, .user-last-name, .password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
        ).prop("readonly", false);
        $(".city, .state, .country").prop("disabled", false);
        if (enterEmail.length == 0) {
            $("#editUserEmail").val(editEmail);
            $(".city, .state, .country").val("").trigger("change");
        }
        return false;
    }

    if (enterEmail.length) {
        renderUserSearchEmailData(enterEmail);
    } else {
        $(".search-email-list").addClass("d-none");
        $(
            ".user-first-name, .user-last-name, .password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
        ).prop("readonly", false);
        $(".city, .state, .country").prop("disabled", false);
        if (
            isEmpty($(".user-first-name").val()) ||
            isEmpty($(".user-last-name").val())
        ) {
            $(".user-first-name, .user-last-name").val("");
        }
        $(
            ".password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
        ).val("");
        $(".city, .state, .country").val("").trigger("change");
    }
});

function renderUserSearchEmailData(enterEmail) {
    $(".search-email-list").html("");

    $.ajax({
        url: route("search.users", [enterEmail]),
        type: "GET",
        success: function (result) {
            $(".search-email-list").removeClass("d-none");
            $(
                ".user-first-name, .user-last-name, .password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
            ).prop("readonly", false);
            $(".city, .state, .country").prop("disabled", false);
            if (result.data.length == 0) {
                if (
                    isEmpty($(".user-first-name").val()) ||
                    isEmpty($(".user-last-name").val())
                ) {
                    $(".user-first-name, .user-last-name").val("");
                }
                $(
                    ".password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
                ).val("");
                $(".city, .state, .country").val("").trigger("change");
                $(".search-email-list").html(
                    `<li><a href="javascript:void(0)" class="text-decoration-none cursor-default text-gray-900" data-turbo="false">No result found.</a></li>`
                );
            } else {
                $(
                    ".user-first-name, .user-last-name, .password, .c-password, .user-contact-number, .website, .postal-code, .address, .note, .company-name, .vat-no"
                ).prop("readonly", true);
                $(".city, .state, .country").prop("disabled", true);
                $(".search-email-list").html("");
                $.each(result.data, function (i, val) {
                    $(".search-email-list").append(
                        $(
                            `<li class="email-confirm-popup" data-id="${val.id}"><a href="#" class="text-decoration-none" data-turbo="false">${val.email}</a></li>`
                        )
                    );
                });

                $(".search-email-list").show().focus().click();
            }
        },
        error: function error(result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listen("click", ".email-confirm-popup", function (event) {
    $(".search-email-list").hide();
    let userId = $(event.currentTarget).attr("data-id");

    $.ajax({
        url: route("get.user", [userId]),
        type: "GET",
        success: function (result) {
            if (result.success) {
                let user = result.data;
                $(".user-first-name").val(user.first_name);
                $(".user-last-name").val(user.last_name);
                if (!isEmpty(user.contact)) {
                    phoneNo = user.region_code + user.contact;
                    $(".user-contact-number")
                        .val(user.contact)
                        .trigger("change");
                    $("#prefix_code").val(user.region_code);
                }
                $(".user-email").val(user.email);
                $(".previewImage").attr(
                    "style",
                    "background-image:url(" + user.profile_image + ")"
                );
                $(".website").val(user.client.website);
                $(".postal-code").val(user.client.postal_code);
                $(".address").val(user.client.address);
                $(".note").val(user.client.note);
                $(".company-name").val(user.client.company_name);
                $(".vat-no").val(user.client.vat_no);
                $(".country").val(user.client.country_id).trigger("change");
                setTimeout(function () {
                    $(".state").val(user.client.state_id).trigger("change");
                }, 500);
                setTimeout(function () {
                    $(".city").val(user.client.city_id).trigger("change");
                }, 1000);

                $(".search-email-list").removeClass("d-none");
            }
        },
        error: function error(result) {
            displayErrorMessage(result.message);
        },
    });
});
