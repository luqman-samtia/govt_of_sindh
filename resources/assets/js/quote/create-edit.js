let discountType = null;
let momentFormat = "";

document.addEventListener("turbo:load", loadCreateEditQuote);

function loadCreateEditQuote() {
    $('input:text:not([readonly="readonly"])').first().blur();
    initializeSelect2CreateEditQuote();
    loadSelect2ClientData();

    momentFormat = convertToMomentFormat(currentDateFormat);

    if (
        $("#quoteNoteData").val() == true ||
        $("#quoteTermData").val() == true
    ) {
        $("#quoteAddNote").hide();
        $("#quoteRemoveNote").show();
        $("#quoteNoteAdd").show();
        $("#quoteTermRemove").show();
    } else {
        $("#quoteRemoveNote").hide();
        $("#quoteNoteAdd").hide();
        $("#quoteTermRemove").hide();
    }
    if ($("#quoteRecurring").val() == true) {
        $(".recurring").show();
    } else {
        $(".recurring").hide();
    }
    if ($("#formData_recurring-1").prop("checked")) {
        $(".recurring").hide();
    }
    if ($("#discountType").val() != 0) {
        $("#discount").removeAttr("disabled");
    } else {
        $("#discount").attr("disabled", "disabled");
    }
    calculateAndSetQuoteAmount();
}
function loadSelect2ClientData() {
    if (!$("#client_id").length && !$("#discountType").length) {
        return;
    }

    $("#client_id,#discountType,#status,#templateId").select2();
}

function initializeSelect2CreateEditQuote() {
    if (!select2NotExists(".product-quote")) {
        return false;
    }
    removeSelect2Container([".product-quote"]);

    $(".product-quote").select2({
        tags: true,
    });

    $(".tax").select2({
        placeholder: "Select TAX",
    });
    $(".payment-qr-code").select2();

    $("#client_id").focus();
    let currentDate = moment(new Date())
        .add(1, "days")
        .format(convertToMomentFormat(currentDateFormat));
    let quoteDueDateFlatPicker = $("#quoteDueDate").flatpickr({
        defaultDate: currentDate,
        dateFormat: currentDateFormat,
        locale: getUserLanguages,
        disableMobile: true,
    });

    let editQuoteDueDateVal = moment($("#editQuoteDueDateAdmin").val()).format(
        convertToMomentFormat(currentDateFormat)
    );
    let editQuoteDueDateFlatPicker = $(".edit-quote-due-date").flatpickr({
        dateFormat: currentDateFormat,
        defaultDate: editQuoteDueDateVal,
        locale: getUserLanguages,
        disableMobile: true,
    });

    $("#quote_date").flatpickr({
        defaultDate: moment(new Date()).format(
            convertToMomentFormat(currentDateFormat)
        ),
        dateFormat: currentDateFormat,
        locale: getUserLanguages,
        disableMobile: true,
        onChange: function () {
            let minDate;
            if (
                currentDateFormat == "d.m.Y" ||
                currentDateFormat == "d/m/Y" ||
                currentDateFormat == "d-m-Y"
            ) {
                minDate = moment($("#quote_date").val(), momentFormat)
                    .add(1, "days")
                    .format(momentFormat);
            } else {
                minDate = moment($("#quote_date").val())
                    .add(1, "days")
                    .format(convertToMomentFormat(currentDateFormat));
            }

            if (typeof quoteDueDateFlatPicker != "undefined") {
                quoteDueDateFlatPicker.set("minDate", minDate);
            }
        },
        onReady: function () {
            if (typeof quoteDueDateFlatPicker != "undefined") {
                quoteDueDateFlatPicker.set("minDate", currentDate);
            }
        },
    });

    let editQuoteDate = $("#editQuoteDate").flatpickr({
        dateFormat: currentDateFormat,
        defaultDate: moment($("#editQuoteDateAdmin").val()).format(
            convertToMomentFormat(currentDateFormat)
        ),
        locale: getUserLanguages,
        disableMobile: true,
        onChange: function () {
            let minDate;
            if (
                currentDateFormat == "d.m.Y" ||
                currentDateFormat == "d/m/Y" ||
                currentDateFormat == "d-m-Y"
            ) {
                minDate = moment($("#editQuoteDate").val(), momentFormat)
                    .add(1, "days")
                    .format(momentFormat);
            } else {
                minDate = moment($("#editQuoteDate").val())
                    .add(1, "days")
                    .format(convertToMomentFormat(currentDateFormat));
            }
            if (typeof editQuoteDueDateFlatPicker != "undefined") {
                editQuoteDueDateFlatPicker.set("minDate", minDate);
            }
        },
        onReady: function () {
            let minDate;
            if (
                currentDateFormat == "d.m.Y" ||
                currentDateFormat == "d/m/Y" ||
                currentDateFormat == "d-m-Y"
            ) {
                minDate = moment($("#editQuoteDateAdmin").val(), momentFormat)
                    .add(1, "days")
                    .format(convertToMomentFormat(currentDateFormat));
            } else {
                minDate = moment($("#editQuoteDateAdmin").val())
                    .add(1, "days")
                    .format(convertToMomentFormat(currentDateFormat));
            }
            if (typeof editQuoteDueDateFlatPicker != "undefined") {
                editQuoteDueDateFlatPicker.set("minDate", minDate);
            }
        },
    });
}

listenKeyup("#quoteId", function () {
    return $("#quoteId").val(this.value.toUpperCase());
});

listenClick("#quoteAddNote", function () {
    $("#quoteAddNote").hide();
    $("#quoteRemoveNote").show();
    $("#quoteNoteAdd").show();
    $("#quoteTermRemove").show();
});
listenClick("#quoteRemoveNote", function () {
    $("#quoteAddNote").show();
    $("#quoteRemoveNote").hide();
    $("#quoteNoteAdd").hide();
    $("#quoteTermRemove").hide();
    $("#quoteNote").val("");
    $("#quoteTerm").val("");
    $("#quoteAddNote").show();
});

listenClick("#formData_recurring-0", function () {
    if ($("#formData_recurring-0").prop("checked")) {
        $(".recurring").show();
    } else {
        $(".recurring").hide();
    }
});
listenClick("#formData_recurring-1", function () {
    if ($("#formData_recurring-1").prop("checked")) {
        $(".recurring").hide();
    }
});

listenChange("#discountType", function () {
    discountType = $(this).val();
    $("#discount").val(0);
    if (discountType == 1 || discountType == 2) {
        $("#discount").removeAttr("disabled");
        if (discountType == 2) {
            let value = $("#discount").val();
            $("#discount").val(value.substring(0, 2));
        }
    } else {
        $("#discount").attr("disabled", "disabled");
        $("#discount").val(0);
        $("#quoteDiscountAmount").text("0");
    }
    calculateDiscount();
});

window.isNumberKey = (evt, element) => {
    let charCode = evt.which ? evt.which : event.keyCode;

    return !(
        (charCode !== 46 || $(element).val().indexOf(".") !== -1) &&
        (charCode < 48 || charCode > 57)
    );
};

listenClick("#addQuoteItem", function () {
    let data = {
        products: JSON.parse($("#products").val()),
    };

    let quoteItemHtml = prepareTemplateRender("#quotesItemTemplate", data);

    $(".quote-item-container").append(quoteItemHtml);
    $(".productId").select2({
        placeholder: "Select Product or Enter free text",
        tags: true,
    });
    resetQuoteItemIndex();
});

const resetQuoteItemIndex = () => {
    let index = 1;
    $(".quote-item-container>tr").each(function () {
        $(this).find(".item-number").text(index);
        index++;
    });
    if (index - 1 == 0) {
        let data = {
            products: JSON.parse($("#products").val()),
        };
        let quoteItemHtml = prepareTemplateRender("#quotesItemTemplate", data);
        $(".quote-item-container").append(quoteItemHtml);
        $(".productId").select2();
    }
};

listenClick(".delete-quote-item", function () {
    $(this).parents("tr").remove();
    resetQuoteItemIndex();
    calculateAndSetQuoteAmount();
});

listenChange(".product-quote", function () {
    let productId = $(this).val();
    if (isEmpty(productId)) {
        productId = 0;
    }
    let element = $(this);
    $.ajax({
        url: route("quotes.get-product", productId),
        type: "get",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                let price = "";
                $.each(result.data, function (id, productPrice) {
                    if (id === productId) price = productPrice;
                });
                element.parent().parent().find("td .price-quote").val(price);
                element.parent().parent().find("td .qty-quote").val(1);
                $(".price-quote").trigger("keyup");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenKeyup(".qty-quote", function () {
    let qty = parseFloat($(this).val());
    let rate = $(this).parent().siblings().find(".price-quote").val();
    rate = parseFloat(removeCommas(rate));
    let amount = calculateAmount(qty, rate);
    $(this)
        .parent()
        .siblings(".quote-item-total")
        .text(addCommas(amount.toFixed(2).toString()));
    calculateAndSetQuoteAmount();
});

listenKeyup(".price-quote", function () {
    let rate = $(this).val();
    rate = parseFloat(removeCommas(rate));
    let qty = $(this).parent().siblings().find(".qty-quote").val();
    let amount = calculateAmount(qty, rate);
    $(this)
        .parent()
        .siblings(".quote-item-total")
        .text(addCommas(amount.toFixed(2).toString()));
    calculateAndSetQuoteAmount();
});

const calculateAmount = (qty, rate) => {
    if (qty > 0 && rate > 0) {
        let price = qty * rate;
        return price;
    } else {
        return 0;
    }
};

const calculateAndSetQuoteAmount = () => {
    let quoteTotalAmount = 0;
    $(".quote-item-container>tr").each(function () {
        let quoteItemTotal = $(this).find(".quote-item-total").text();
        quoteItemTotal = removeCommas(quoteItemTotal);
        quoteItemTotal = isEmpty($.trim(quoteItemTotal))
            ? 0
            : parseFloat(quoteItemTotal);
        quoteTotalAmount += quoteItemTotal;
    });

    quoteTotalAmount = parseFloat(quoteTotalAmount);
    if (isNaN(quoteTotalAmount)) {
        quoteTotalAmount = 0;
    }
    $("#quoteTotal").text(addCommas(quoteTotalAmount.toFixed(2)));

    //set hidden input value
    $("#quoteTotalAmount").val(quoteTotalAmount);

    calculateDiscount();
};

const calculateDiscount = () => {
    let discount = $("#discount").val();
    discountType = $("#discountType").val();
    let itemAmount = [];
    let i = 0;
    $(".quote-item-total").each(function () {
        itemAmount[i++] = $.trim(removeCommas($(this).text()));
    });
    $.sum = function (arr) {
        var r = 0;
        $.each(arr, function (i, v) {
            r += +v;
        });
        return r;
    };

    let totalAmount = $.sum(itemAmount);
    $("#quoteTotal").text(number_format(totalAmount));
    if (isEmpty(discount) || isEmpty(totalAmount)) {
        discount = 0;
    }
    let discountAmount = 0;
    let finalAmount = totalAmount - discountAmount;
    if (discountType == 1) {
        discountAmount = discount;
        finalAmount = totalAmount - discountAmount;
    } else if (discountType == 2) {
        discountAmount = (totalAmount * discount) / 100;
        finalAmount = totalAmount - discountAmount;
    }
    $("#quoteFinalAmount").text(number_format(finalAmount));
    $("#quoteTotalAmount").val(finalAmount.toFixed(2));
    $("#quoteDiscountAmount").text(number_format(discountAmount));
};

listen("keyup", "#discount", function () {
    let value = $(this).val();
    if (discountType == 2 && value > 100) {
        displayErrorMessage(
            "On Percentage you can only give maximum 100% discount"
        );
        $(this).val(value.slice(0, -1));

        return false;
    }
    calculateDiscount();
});

listenClick("#saveAsDraftQuote", function (event) {
    event.preventDefault();

    let quoteStates = $(this).data("status");
    let myForm = document.getElementById("quoteForm");
    let formData = new FormData(myForm);
    formData.append("status", quoteStates);
    screenLock();
    $.ajax({
        url: route("quotes.store"),
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            // displaySuccessMessage(result.message)
            Turbo.visit(route("quotes.index"));
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
            screenUnLock();
        },
    });
});

listenClick("#editSaveQuote", function (event) {
    event.preventDefault();
    let quoteStatus = $(this).data("status");

    let formData =
        $("#quoteEditForm").serialize() + "&quoteStatus=" + quoteStatus;
    screenLock();
    $.ajax({
        url: $("#quoteUpdateUrl").val(),
        type: "PUT",
        dataType: "json",
        data: formData,
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            // displaySuccessMessage(result.message)
            Turbo.visit(route("quotes.index"));
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
            screenUnLock();
        },
    });
});

listen("input", ".qty-quote", function () {
    let quantity = $(this).val();
    if (quantity.length == 8) {
        $(this).val(quantity.slice(0, -1));
    }
});
