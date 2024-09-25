let paymentTableName = "";

document.addEventListener("turbo:load", loadPayment);

function loadPayment() {
    initializeSelect2Payment();
    paymentDateFilter();
    paymentTableName = "#tblPayments";
}

function initializeSelect2Payment() {
    if (!select2NotExists("#adminPaymentInvoiceId")) {
        return false;
    }

    if ($("#adminPaymentInvoiceId").hasClass("select2-hidden-accessible")) {
        $("#adminPaymentInvoiceId .select2-container").remove();
    }

    $("#adminPaymentInvoiceId").select2({
        dropdownParent: $("#paymentModal"),
    });
}

listenClick(".admin-payment-delete-btn", function (event) {
    let paymentId = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("payments.destroy", paymentId),
        paymentTableName,
        Lang.get("messages.invoice.payment")
    );
});

listenClick(".addPayment", function () {
    let currentDtFormat = currentDateFormat;
    $.ajax({
        url: route("get-current-date-format"),
        type: "get",
        success: function (data) {
            currentDtFormat = data;
            $("#payment_date").flatpickr({
                defaultDate: new Date(),
                dateFormat: data,
                maxDate: new Date(),
                locale: getUserLanguages,
                disableMobile: true,
            });
            $("#paymentModal").appendTo("body").modal("show");
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });

    setTimeout(function () {
        $("#adminPaymentInvoiceId").select2({
            dropdownParent: $("#paymentModal"),
        });
    }, 200);
});

function getCurrentDateFormat() {
    let currentDtFormat = currentDateFormat;
    $.ajax({
        url: route("get-current-date-format"),
        type: "get",
        success: function (data) {
            currentDtFormat = data;
            return currentDtFormat;
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            return currentDtFormat;
        },
    });

    return currentDtFormat;
}

listenHiddenBsModal("#paymentModal", function () {
    $("#adminPaymentInvoiceId").val(null).trigger("change");
    resetModalForm("#paymentForm");
});

listenSubmit("#paymentForm", function (e) {
    e.preventDefault();

    if ($("#payment_note").val().trim().length == 0) {
        displayErrorMessage("Note field is Required");
        return false;
    }

    let btnSubmitEle = $(this).find("#btnPay");
    setAdminBtnLoader(btnSubmitEle);
    $.ajax({
        url: route("payments.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                setAdminBtnLoader(btnSubmitEle);
                $("#paymentModal").modal("hide");
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            setAdminBtnLoader(btnSubmitEle);
        },
    });
});

listenChange(".invoice", function () {
    let invoiceId = $(this).val();
    if (isEmpty(invoiceId)) {
        $("#due_amount").val(0);
        $("#paid_amount").val(0);
        return false;
    }
    $.ajax({
        url: route("payments.get-invoiceAmount", invoiceId),
        type: "get",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                $(".invoice-currency-code").text(result.data.currencyCode);
                $("#due_amount").val(number_format(result.data.totalDueAmount));
                $("#paid_amount").val(
                    number_format(result.data.totalPaidAmount)
                );
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick(".admin-payment-edit-btn", function (event) {
    let paymentId = $(event.currentTarget).attr("data-id");
    paymentRenderData(paymentId);
});

function paymentRenderData(paymentId) {
    $.ajax({
        url: route("payments.edit", paymentId),
        type: "GET",
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#edit_invoice_id").val(result.data.invoice.invoice_id);
                $("#edit_amount").val(result.data.amount);
                $("#edit_payment_date").flatpickr({
                    defaultDate: result.data.payment_date,
                    dateFormat: currentDateFormat,
                    maxDate: new Date(),
                    locale: getUserLanguages,
                    disableMobile: true,
                });
                $(".edit-invoice-currency-code").text(result.data.currencyCode);
                $("#edit_payment_note").val(result.data.notes);
                $("#paymentId").val(result.data.id);
                $("#transactionId").val(result.data.payment_id);
                $("#invoice").val(result.data.invoice_id);
                $("#totalDue_amount").val(
                    number_format(
                        result.data.DueAmount.original.data.totalDueAmount
                    )
                );
                $("#totalPaid_amount").val(
                    number_format(
                        result.data.DueAmount.original.data.totalPaidAmount
                    )
                );
                $("#editPaymentModal").appendTo("body").modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
        },
    });
}

listenSubmit("#editPaymentForm", function (event) {
    event.preventDefault();

    const paymentId = $("#paymentId").val();
    $.ajax({
        url: route("payments.update", { payment: paymentId }),
        type: "put",
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#editPaymentModal").modal("hide");
                $("#tblPayments").DataTable().ajax.reload(null, false);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
        },
    });
});

listenChange(".transaction-approve", function () {
    let id = $(this).attr("data-id");
    let status = $(this).val();

    $.ajax({
        url: route("change-transaction-status", id),
        type: "GET",
        data: { id: id, status: status },
        success: function (result) {
            displaySuccessMessage(result.message);
            livewire.emit("refreshDatatable");
            livewire.emit("resetPageTable");
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenChange("#paymentAttachment", function () {
    let ext = $(this).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["png", "jpg", "jpeg", "pdf"]) == -1) {
        displayErrorMessage(
            "The attachment must be a file of type: jpg, png, jpeg, pdf"
        );
        $(this).val("");
    }
    let imageSize = 0;
    for (let i = 0; i < this.files.length; i++) {
        imageSize += this.files[i].size;
    }
    if (imageSize >= 10485760) {
        displayErrorMessage("The maximum attachment size 10 mb allowed.");
        $(this).val("");
    }
    return false;
});

listen("input", "#amount", function () {
    let amount = $(this).val();
    if (amount.charAt(amount.length - 4) === ".") {
        $(this).val(amount.slice(0, -1));
    }
});

function paymentDateFilter() {
    var start = moment().startOf("month");
    var end = moment().endOf("month");

    if (!$("#paymentDateFilter").length) {
        return;
    }

    let dateRange = $("#paymentDateFilter");

    function cb(start, end) {
        window.livewire.emit(
            "dateFilter",
            start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
        );
        $("#paymentDateFilter").val(
            start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
        );
    }

    dateRange.daterangepicker(
        {
            startDate: start,
            endDate: end,
            opens: "left",
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                customRangeLabel: Lang.get("messages.common.custom"),
                applyLabel: Lang.get("messages.common.apply"),
                cancelLabel: Lang.get("messages.common.cancel"),
                fromLabel: Lang.get("messages.common.from"),
                toLabel: Lang.get("messages.common.to"),
                monthNames: [
                    Lang.get("messages.months.jan"),
                    Lang.get("messages.months.feb"),
                    Lang.get("messages.months.mar"),
                    Lang.get("messages.months.apr"),
                    Lang.get("messages.months.may"),
                    Lang.get("messages.months.jun"),
                    Lang.get("messages.months.jul"),
                    Lang.get("messages.months.aug"),
                    Lang.get("messages.months.sep"),
                    Lang.get("messages.months.oct"),
                    Lang.get("messages.months.nov"),
                    Lang.get("messages.months.dec"),
                ],
                daysOfWeek: [
                    Lang.get("messages.weekdays.sun"),
                    Lang.get("messages.weekdays.mon"),
                    Lang.get("messages.weekdays.tue"),
                    Lang.get("messages.weekdays.wed"),
                    Lang.get("messages.weekdays.thu"),
                    Lang.get("messages.weekdays.fri"),
                    Lang.get("messages.weekdays.sat"),
                ],
            },
            ranges: {
                [Lang.get("messages.range.today")]: [moment(), moment()],
                [Lang.get("messages.range.yesterday")]: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                [Lang.get("messages.range.last_week")]: [
                    moment().subtract(6, "days"),
                    moment(),
                ],
                [Lang.get("messages.range.last_30")]: [
                    moment().subtract(29, "days"),
                    moment(),
                ],
                [Lang.get("messages.range.this_month")]: [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                [Lang.get("messages.range.last_month")]: [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        cb
    );

    cb(start, end);

    dateRange.on("apply.daterangepicker", function (ev, picker) {
        isPickerApply = true;
        start = picker.startDate.format("YYYY-MM-DD");
        end = picker.endDate.format("YYYY-MM-DD");
        window.livewire.emit("dateFilter", start + " - " + end);
    });
}

listenClick("#adminPaymentExcelExport", function (e) {
    e.preventDefault();

    $.ajax({
        url: route("admin.paymentsExcel"),
        type: "GET",
        data: { date: $("#paymentDateFilter").val() },
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            let blob = new Blob([response]);
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "Payment-Excel" + ".xlsx";
            link.click();
        },
        error: function (blob) {
            console.log(blob);
        },
    });
});

listenClick("#adminPaymentPdfExport", function (e) {
    e.preventDefault();

    $.ajax({
        url: route("admin.payments.pdf"),
        type: "GET",
        data: { date: $("#paymentDateFilter").val() },
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            let blob = new Blob([response]);
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "payments" + ".pdf";
            link.click();
        },
        error: function (blob) {
            console.log(blob);
        },
    });
});

// public payment invoice JS code
listenClick(".open-public-payment-modal", function (event) {
    $("#publicPaymentModal").appendTo("body").modal("show");
});

// public payment store JS code
listenSubmit("#publicPaymentForm", function (e) {
    e.preventDefault();

    if ($("#payment_note").val().trim().length == 0) {
        displayErrorMessage("Note field is Required");
        return false;
    }

    let btnSubmitEle = $(this).find("#btnPublicPay");
    setAdminBtnLoader(btnSubmitEle);
    let payloadData = {
        amount: parseFloat($("#amount").val()),
        invoiceId: parseInt($("#invoice_id").val()),
        notes: $("#payment_note").val(),
    };

    if (paymentMode == 1) {
        $.ajax({
            url: route("client.payments.store"),
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 2) {
        $.post(route("stripe-payment"), payloadData)
            .done((result) => {
                let sessionId = result.data.sessionId;
                stripe
                    .redirectToCheckout({
                        sessionId: sessionId,
                    })
                    .then(function (result) {
                        displayErrorMessage(error.message);
                        setAdminBtnLoader(btnSubmitEle);
                    });
            })
            .catch((error) => {
                displayErrorMessage(error.responseJSON.message);
                setAdminBtnLoader(btnSubmitEle);
            });
    } else if (paymentMode == 3) {
        $.ajax({
            type: "GET",
            url: route("public.paypal.init"),
            data: {
                amount: payloadData.amount,
                invoiceId: payloadData.invoiceId,
                notes: payloadData.notes,
            },
            success: function (result) {
                if (result.status == "CREATED") {
                    let redirectTo = "";

                    $.each(result.links, function (key, val) {
                        if (val.rel == "approve") {
                            redirectTo = val.href;
                        }
                    });
                    location.href = redirectTo;
                } else {
                    location.href = result.url;
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 5) {
        $.ajax({
            type: "GET",
            url: route("public.razorpay.init"),
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    $("#publicPaymentModal").modal("hide");
                    let {
                        id,
                        amount,
                        name,
                        email,
                        invoiceId,
                        invoice_id,
                        notes,
                    } = result.data;
                    options.description = JSON.stringify({
                        invoice_id: invoice_id,
                        notes: notes,
                    });
                    options.order_id = id;
                    options.amount = amount;
                    options.prefill.name = name;
                    options.prefill.email = email;
                    options.prefill.invoiceId = invoiceId;
                    let razorPay = new Razorpay(options);
                    razorPay.open();
                    razorPay.on("public.payment.failed");
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 6) {
        window.location.replace(
            route("paystack.init", {
                invoiceId: payloadData.invoiceId,
                amount: payloadData.amount,
                note: payloadData.transactionNotes,
            })
        );
    }
});

listenHiddenBsModal("#publicPaymentModal", function () {
    resetModalForm("#publicPaymentForm", "#publicPaymentValidationErrorsBox");
});
