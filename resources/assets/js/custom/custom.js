let source = null;
let jsrender = require("jsrender");
require("alpinejs");

document.addEventListener("turbo:load", initAllComponents);

function initAllComponents() {
    refreshCsrfToken();
    select2initialize();
    modalInputFocus();
    alertInitialize();
    IOInitImageComponent();
    IOInitSidebar();
    togglePassword();
    tooltip();
    resizeWindow();
}

function togglePassword() {
    $('[data-toggle="password"]').each(function () {
        var input = $(this);
        var eye_btn = $(this).parent().find(".input-icon");
        eye_btn.css("cursor", "pointer").addClass("input-password-hide");
        eye_btn.on("click", function () {
            if (eye_btn.hasClass("input-password-hide")) {
                eye_btn
                    .removeClass("input-password-hide")
                    .addClass("input-password-show");
                eye_btn
                    .find(".bi")
                    .removeClass("bi-eye-slash-fill")
                    .addClass("bi-eye-fill");
                input.attr("type", "text");
            } else {
                eye_btn
                    .removeClass("input-password-show")
                    .addClass("input-password-hide");
                eye_btn
                    .find(".bi")
                    .removeClass("bi-eye-fill")
                    .addClass("bi-eye-slash-fill");
                input.attr("type", "password");
            }
        });
    });
}

function alertInitialize() {
    $(".alert").delay(5000).slideUp(300);
}

function select2initialize() {
    $('[data-control="select2"]').each(function () {
        $(this).select2();
    });

    $("#changeUserId").select2();
}

function refreshCsrfToken() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
}

window.addEventListener("turbo:load", (event) => {
    $(
        'input:text:not([readonly="readonly"]):not([name="search"]):not(.front-input):not(.removeFocus)'
    )
        .first()
        .focus();
});

const modalInputFocus = () => {
    $(function () {
        $(".modal").on("shown.bs.modal", function () {
            if ($(this).find("input:text")[0]) {
                $(this).find("input:text")[0].focus();
            }
        });
    });
};

window.ajaxCallInProgress = function () {
    ajaxCallIsRunning = true;
};

window.ajaxCallCompleted = function () {
    ajaxCallIsRunning = false;
};

toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

window.resetModalForm = function (formId, validationBox) {
    if ($(formId)[0] == null) {
        return false;
    }
    $(formId)[0].reset();
    $("select.select2Selector").each(function (index, element) {
        let drpSelector = "#" + $(this).attr("id");
        $(drpSelector).val("");
        $(drpSelector).trigger("change");
    });
    $(validationBox).hide();
};

window.printErrorMessage = function (selector, errorResult) {
    $(selector).show().html("");
    $(selector).text(errorResult.responseJSON.message);
};

window.manageAjaxErrors = function (data) {
    var errorDivId =
        arguments.length > 1 && arguments[1] !== undefined
            ? arguments[1]
            : "editValidationErrorsBox";
    if (data.status == 404) {
        toastr.error(data.responseJSON.message);
    } else {
        printErrorMessage("#" + errorDivId, data);
    }
};

window.displaySuccessMessage = function (message) {
    toastr.success(message);
};

window.displayErrorMessage = function (message) {
    toastr.error(message);
};

window.deleteItem = function (url, tableId, header, callFunction = null) {
    var callFunction =
        arguments.length > 3 && arguments[3] !== undefined
            ? arguments[3]
            : null;
    swal({
        title: Lang.get("messages.common.delete") + " !",
        text:
            Lang.get("messages.common.are_you_sure_delete") +
            ' "' +
            header +
            '" ?',
        buttons: [
            Lang.get("messages.common.no_cancel"),
            Lang.get("messages.common.yes_delete"),
        ],
        icon: sweetAlertIcon,
    }).then(function (willDelete) {
        if (willDelete) {
            deleteItemAjax(url, header, callFunction);
        }
    });
};

function deleteItemAjax(url, header, callFunction = null) {
    $.ajax({
        url: url,
        type: "DELETE",
        dataType: "json",
        success: function (obj) {
            if (obj.success) {
                window.livewire.emit("refreshDatatable");
                window.livewire.emit("resetPageTable");
            }
            swal({
                icon: "success",
                title: Lang.get("messages.common.deleted"),
                text:
                    header + " " + Lang.get("messages.common.has_been_deleted"),
                timer: 2000,
                button: Lang.get("messages.common.ok"),
            });
            if (callFunction) {
                eval(callFunction);
            }
        },
        error: function (data) {
            swal({
                title: Lang.get("messages.common.error"),
                icon: "error",
                text: data.responseJSON.message,
                type: "error",
                timer: 4000,
                button: Lang.get("messages.common.ok"),
            });
        },
    });
}

window.format = function (dateTime) {
    var format =
        arguments.length > 1 && arguments[1] !== undefined
            ? arguments[1]
            : "DD-MMM-YYYY";
    return moment(dateTime).format(format);
};

window.prepareTemplateRender = function (templateSelector, data) {
    let template = jsrender.templates(templateSelector);
    return template.render(data);
};

window.isValidFile = function (inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["gif", "png", "jpg", "jpeg"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector).removeClass("d-none");
        $(validationMessageSelector)
            .html("The image must be a file of type: jpeg, jpg, png.")
            .show();
        $(validationMessageSelector).delay(5000).slideUp(300);

        return false;
    }
    $(validationMessageSelector).hide();
    return true;
};

window.removeCommas = function (str) {
    if (str === undefined) {
        return str;
    }
    return str.replace(/,/g, "");
};

window.DatetimepickerDefaults = function (opts) {
    return $.extend(
        {},
        {
            sideBySide: true,
            ignoreReadonly: true,
            icons: {
                close: "fa fa-times",
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-clock-o",
                clear: "fa fa-trash-o",
            },
        },
        opts
    );
};

window.isEmpty = (value) => {
    return value === undefined || value === null || value === "";
};

window.screenLock = function () {
    $("#overlay-screen-lock").show();
    $("body").css({ "pointer-events": "none", opacity: "0.6" });
};

window.screenUnLock = function () {
    $("body").css({ "pointer-events": "auto", opacity: "1" });
    $("#overlay-screen-lock").hide();
};

window.processingBtn = function (selecter, btnId, state = null) {
    var loadingButton = $(selecter).find(btnId);
    if (state === "loading") {
        loadingButton.button("loading");
    } else {
        loadingButton.button("reset");
    }
};

window.setAdminBtnLoader = function (btnLoader) {
    if (btnLoader.attr("data-loading-text")) {
        btnLoader
            .html(btnLoader.attr("data-loading-text"))
            .prop("disabled", true);
        btnLoader.removeAttr("data-loading-text");
        return;
    }
    btnLoader.attr("data-old-text", btnLoader.text());
    btnLoader.html(btnLoader.attr("data-new-text")).prop("disabled", false);
};

window.onload = function () {
    window.startLoader = function () {
        $(".infy-loader").show();
    };

    window.stopLoader = function () {
        $(".infy-loader").hide();
    };

    // infy loader js
    stopLoader();
};

$(document).ready(function () {
    // script to active parent menu if sub menu has currently active
    let hasActiveMenu = $(document)
        .find(".nav-item.dropdown ul li")
        .hasClass("active");
    if (hasActiveMenu) {
        $(document)
            .find(".nav-item.dropdown ul li.active")
            .parent("ul")
            .css("display", "block");
        $(document)
            .find(".nav-item.dropdown ul li.active")
            .parent("ul")
            .parent("li")
            .addClass("active");
    }

    $(document).on("click", "#kt_aside_toggle", function () {
        $(".sidebar-search-box").toggleClass("show");
    });
});

window.urlValidation = function (value, regex) {
    let urlCheck = value == "" ? true : value.match(regex) ? true : false;
    if (!urlCheck) {
        return false;
    }

    return true;
};

if ($(window).width() > 992) {
    $(document).on("click", ".no-hover", function () {
        $(this).toggleClass("open");
    });
}

listen("click", "#register", function (e) {
    e.preventDefault();
    $(".open #dropdownLanguage").trigger("click");
    $(".open #dropdownLogin").trigger("click");
});

listen("click", "#language", function (e) {
    e.preventDefault();
    $(".open #dropdownRegister").trigger("click");
    $(".open #dropdownLogin").trigger("click");
});

listen("click", "#login", function (e) {
    e.preventDefault();
    $(".open #dropdownRegister").trigger("click");
    $(".open #dropdownLanguage").trigger("click");
});

window.checkSummerNoteEmpty = function (
    selectorElement,
    errorMessage,
    isRequired = 0
) {
    if ($(selectorElement).summernote("isEmpty") && isRequired === 1) {
        displayErrorMessage(errorMessage);
        $(document).find(".note-editable").html("<p><br></p>");
        return false;
    } else if (!$(selectorElement).summernote("isEmpty")) {
        $(document)
            .find(".note-editable")
            .contents()
            .each(function () {
                if (this.nodeType === 3) {
                    // text node
                    this.textContent = this.textContent.replace(/\u00A0/g, "");
                }
            });
        if ($(document).find(".note-editable").text().trim().length == 0) {
            $(document).find(".note-editable").html("<p><br></p>");
            $(selectorElement).val(null);
            if (isRequired === 1) {
                displayErrorMessage(errorMessage);

                return false;
            }
        }
    }

    return true;
};

window.preparedTemplate = function () {
    let source = $("#actionTemplate").html();
    window.preparedTemplate = Handlebars.compile(source);
};

window.avoidSpace = function (event) {
    let k = event ? event.which : window.event.keyCode;
    if (k == 32) {
        return false;
    }
};

// Add comma into numbers
window.addCommas = function (number) {
    number += "";
    let x = number.split(".");
    let x1 = x[0];
    let x2 = x.length > 1 ? "." + x[1] : "";
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1 + x2;
};

// Notification
listen("click", ".notification", function (e) {
    e.stopPropagation();
    let notificationId = $(this).data("id");
    let notification = $(this);
    $('[data-toggle="tooltip"]').tooltip("hide");

    $.ajax({
        type: "get",
        url: "/notification/" + notificationId + "/read",
        success: function () {
            notification.remove();
            let notificationCounter =
                document.getElementsByClassName("notification").length;
            displaySuccessMessage(Lang.get("messages.flash.notification_read"));
            $("#counter").text(notificationCounter);
            if (notificationCounter == 0) {
                $(".empty-state").removeClass("d-none");
                $("#counter").text(notificationCounter);
                $(".notification-count").addClass("d-none");
                $("#readAllNotification").parents("div").first().remove();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});

window.displayDocument = function (input, selector, extension) {
    let displayPreview = true;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let image = new Image();
            if ($.inArray(extension, ["pdf", "doc", "docx"]) == -1) {
                image.src = e.target.result;
            } else {
                if (extension == "pdf") {
                    $("#editPhoto").css(
                        "background-image",
                        'url("' + pdfDocumentImageUrl + '")'
                    );
                    image.src = pdfDocumentImageUrl;
                } else {
                    image.src = docxDocumentImageUrl;
                }
            }
            image.onload = function () {
                $(selector).attr("src", image.src);
                $(selector).css("background-image", 'url("' + image.src + '")');
                displayPreview = true;
            };
        };
        if (displayPreview) {
            reader.readAsDataURL(input.files[0]);
            $(selector).show();
        }
    }
};

listen("click", "#readAllNotification", function (e) {
    e.stopPropagation();

    $.ajax({
        type: "post",
        url: route("read.all.notification"),
        success: function () {
            $(".notification").remove();
            let notificationCounter =
                document.getElementsByClassName("notification").length;
            $("#counter").text(notificationCounter);
            $(".empty-state").removeClass("d-none");
            $(".notification-count").addClass("d-none");
            $("#readAllNotification").parents("div").first().remove();

            displaySuccessMessage(
                Lang.get("messages.flash.all_notification_read")
            );
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});

window.defaultAvatarImagePreview = function (imagePreviewSelector) {
    $(imagePreviewSelector).css(
        "background-image",
        'url("' + $("#defaultAvatarImageUrl").val() + '")'
    );
};

window.defaultImagePreview = function (imagePreviewSelector) {
    $(imagePreviewSelector).css(
        "background-image",
        'url("' + $("#defaultImageUrl").val() + '")'
    );
};

window.wc_hex_is_light = function (color) {
    const hex = color.replace("#", "");
    const c_r = parseInt(hex.substr(0, 2), 16);
    const c_g = parseInt(hex.substr(2, 2), 16);
    const c_b = parseInt(hex.substr(4, 2), 16);
    const brightness = (c_r * 299 + c_g * 587 + c_b * 114) / 1000;
    return brightness > 240;
};

window.number_format = function (number, decimals = 2) {
    let dec_point = decimalsSeparator;
    let thousands_sep = thousandsSeparator;
    // Strip all characters but numerical ones.
    number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
};

$(document).on(
    "focus",
    ".select2-selection.select2-selection--single",
    function (e) {
        $(this)
            .closest(".select2-container")
            .siblings("select:enabled")
            .select2("open");
    }
);

$(document).on("select2:open", () => {
    let allFound = document.querySelectorAll(
        ".select2-container--open .select2-search__field"
    );
    allFound[allFound.length - 1].focus();
});

window.blockSpecialChar = function (e) {
    let k;
    document.all ? (k = e.keyCode) : (k = e.which);
    return (
        (k > 64 && k < 91) ||
        (k > 96 && k < 123) ||
        k == 8 ||
        k == 32 ||
        (k >= 48 && k <= 57)
    );
};

window.isDoubleClicked = function (element) {
    //if already clicked return TRUE to indicate this click is not allowed
    if (element.data("isclicked")) return true;

    //mark as clicked for 1 second
    element.data("isclicked", true);
    setTimeout(function () {
        element.removeData("isclicked");
    }, 1000);

    //return FALSE to indicate this click was allowed
    return false;
};

window.fnc = function (value, min, max) {
    if (parseInt(value) < 0 || isNaN(value)) return 0;
    else if (parseInt(value) > 100) return "Number is greater than 100";
    else return value;
};

listen("click", ".changeLanguage", function (e) {
    let languageName = $(this).data("prefix-value");
    $.ajax({
        url: route("change-language"),
        type: "post",
        data: { languageName: languageName },
        success: function (result) {
            displaySuccessMessage(Lang.get("messages.flash.language_updated"));
            setTimeout(function () {
                location.reload();
            }, 1000);
        },
    });
});

window.convertToMomentFormat = function (format) {
    switch (format) {
        case "d-m-Y":
            return "DD-MM-YYYY";
        case "m-d-Y":
            return "MM-DD-YYYY";
        case "Y-m-d":
            return "YYYY-MM-DD";
        case "m/d/Y":
            return "MM/DD/YYYY";
        case "d/m/Y":
            return "DD/MM/YYYY";
        case "Y/m/d":
            return "YYYY/MM/DD";
        case "m.d.Y":
            return "MM.DD.YYYY";
        case "d.m.Y":
            return "DD.MM.YYYY";
        case "Y.m.d":
            return "YYYY.MM.DD";
        default:
        // code block
    }
};

window.copyToClipboard = function (element) {
    let $temp = $("<input>");
    $("body").append($temp);
    $temp.val(element).select();
    document.execCommand("copy");
    $temp.remove();
    displaySuccessMessage(Lang.get("messages.common.copied_successfully"));
};

listen("click", function (event) {
    let target = $(event.target);
    if (!target.closest(".dropdown-menu").length) {
        $(".livewire-search-box .dropdown-menu").removeClass("show");
        $(".livewire-search-box .fw-bolder").removeAttr("aria-expanded");
    }
});

listenClick(".filter-popup", function (event) {
    event.preventDefault();
    $(".livewire-search-box .dropdown-menu").addClass("show");
});

window.addEventListener(
    "keydown",
    function (event) {
        if (event.keyCode == 27) {
            $(".livewire-search-box .dropdown-menu").removeClass("show");
        }
    },
    true
);

window.select2NotExists = (element) => {
    let targetEle = $(element);
    if (!targetEle.length) {
        return false;
    }

    return true;
};

window.removeSelect2Container = (elements) => {
    elements.forEach(function (value) {
        if ($(value).hasClass("select2-hidden-accessible")) {
            $(".select2-container").remove();
        }
    });
};

listenClick(".apply-dark-mode", function (e) {
    e.preventDefault();
    $.ajax({
        url: route("update-dark-mode"),
        type: "get",
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(Lang.get("messages.flash.theme_changed"));
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

function tooltip() {
    let tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function resizeWindow() {
    $(window).resize(function () {
        if ($(window).width() < 425) {
            $(".createInvoiceBtn").html('<i class="far fa-file-alt"></i>');
        } else {
            $(".createInvoiceBtn").html(
                Lang.get("messages.invoice.new_invoice")
            );
        }
    });
    $(window).trigger("resize");
}

window.currencyAmount = function (amount) {
    if ($("#currency_position").val() == 1) {
        return " " + number_format(amount) + " " + $("#currency").val();
    }
    return " " + $("#currency").val() + " " + number_format(amount);
};

listenChange(".image-upload", function () {
    let ext = $(this).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["png", "jpg", "jpeg"]) == -1) {
        displayErrorMessage("The image must be a file of type: jpg, png, jpeg");
        $(this).val("");
    }
});

listenChange(".change-tenant-client", function () {
    let tenantId = $(this).val();

    $.ajax({
        url: route("change.tenant.client"),
        type: "POST",
        data: { tenantId: tenantId },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                window.location.href = route("client.dashboard");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

// open send invoice whatsapp modal JS code for admin and client
listenClick(".send-whatsapp-invoice-modal", function (e) {
    let invoiceId = $(this).attr("data-id");
    $("#sendWhatsAppModal").appendTo("body").modal("show");
    $("#sendWhatsAppModal").find("#invoiceId").val(invoiceId);
});

// send invoice whatsapp JS code for admin and client
listenSubmit("#sendInvoiceOnWhatsApp", function (e) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: route("send.invoice.on.whatsapp"),
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                let momentFormat = convertToMomentFormat(currentDateFormat);
                let invoice = result.data.invoice;
                let appName = result.data.appName;
                let phoneNo = result.data.phoneNumber;
                let pdfLink = result.data.invoicePdfLink;
                let name = invoice.client.user.first_name;
                let invoiceNo = invoice.invoice_id;
                let date = moment(invoice.invoice_date).format(momentFormat);
                let dueDate = moment(invoice.due_date).format(momentFormat);
                let paidAmount = 0;
                let dueAmount = 0;

                $.each(invoice.payments, function (key, value) {
                    paidAmount += parseFloat(value.amount);
                });

                dueAmount =
                    parseFloat(invoice.final_amount) - parseFloat(paidAmount);
                let totalAmount = invoice.final_amount.toFixed(2);

                let whatsappLink = `https://api.whatsapp.com/send?phone=${phoneNo}&text=Hello *${name}*,%0a%0aThank you for doing business with *${appName}*.%0aPlease find your invoice details below.%0a%0aInvoice No: ${invoiceNo}%0aInvoice Date: ${date}%0aDue Date: ${dueDate}%0aTotal Amount: ${totalAmount}%0aPaid Amount: ${paidAmount.toFixed(
                    2
                )}%0aDue Amount: ${dueAmount.toFixed(
                    2
                )}%0a%0aYou can view the invoice PDF here: ${pdfLink}`;

                window.open(whatsappLink, "_blank");
                $("#sendWhatsAppModal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.message);
        },
    });
});

// reset send invoice on whatsapp modal JS code
listenHiddenBsModal("#sendWhatsAppModal", function () {
    $(".whatsapp-phone-number").val("");
    $("#valid-msg").addClass("hide");
    $("#error-msg").addClass("hide");
});

window.hideDropdownManually = function (dropdownBtnEle, dropdownEle) {
    dropdownBtnEle.removeClass("show");
    dropdownEle.removeClass("show");
};
