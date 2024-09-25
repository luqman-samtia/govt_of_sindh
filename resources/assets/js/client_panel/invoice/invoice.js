document.addEventListener("turbo:load", loadCPInvoice);

function loadCPInvoice() {
    initializeSelect2CPInvoice();
    initializeSelect2Payment();

    // resetModalForm('#clientPaymentForm', '#error');
    $(".amount").hide();
    let paymentMode = 1;
    let uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        let clean_uri = uri.substring(0, uri.indexOf("?"));
        window.history.replaceState({}, document.title, clean_uri);
    }
}

listenChange("#payment_mode", function () {
    if ($(this).val() == 1) {
        $(".payment-attachment").removeClass("d-none");
    } else {
        $(".payment-attachment").addClass("d-none");
    }
});

function initializeSelect2CPInvoice() {
    if (!select2NotExists("#status_filter")) {
        return false;
    }
    removeSelect2Container([
        "#status_filter",
        "#payment_type",
        "#payment_mode",
    ]);

    $("#status_filter").select2({
        placeholder: "All",
    });
    if ($("#status").val() == "") {
        $("#status_filter").val(5).trigger("change");
    }
}
function initializeSelect2Payment() {
    //Invoice Payments
    // $('#payment_type').select2();
    // $('#payment_mode').select2();
    $("#payment_type").select2({
        placeholder: selectPaymentTypeLang,
        // dropdownParent: $('#clientPaymentModal'),
    });
    $("#payment_mode").select2({
        placeholder: selectPaymentModeLang,
        // dropdownParent: $('#clientPaymentModal'),
    });
}

listenClick("#resetFilter", function () {
    $("#status_filter").val(5).trigger("change");
    $("#status_filter").select2({
        placeholder: "All",
    });
});

listenChange("#payment_mode", function () {
    let value = $(this).val();
    if (value == 1) {
        $("#transaction").show();
    } else {
        $("#transaction").hide();
    }
});

listenChange("#payment_type", function () {
    let value = $(this).val();
    let full_payment = $("#payable_amount").val();

    if (value == "2") {
        $(".amount").hide();
        $("#amount").val(full_payment);
        $("#amount").prop("readonly", true);
    } else if (value == "3") {
        $(".amount").show();
        $("#amount").val("");
        $("#amount").prop("readonly", false);
    } else {
        $(".amount").hide();
        $("#amount").prop("readonly", false);
    }
});

listenKeyup("#amount", function () {
    let payable_amount = parseFloat($("#payable_amount").val());
    let amount = parseFloat($("#amount").val());
    let paymentType = parseInt($("#payment_type").val());
    if (paymentType === 3 && payable_amount < amount) {
        $("#error-msg").text("Amount should be less than payable amount");
        $("#btnPay").addClass("disabled");
    } else if (paymentType === 2 && payable_amount < amount) {
        $("#error-msg").text("Amount should be less than payable amount");
        $("#btnPay").addClass("disabled");
    } else {
        $("#error-msg").text("");
        $("#btnPay").removeClass("disabled");
    }
});

listenSubmit("#clientPaymentForm", function () {
    if ($("#error-msg").text() !== "") {
        return false;
    }
});

listenChange("#payment_mode", function () {
    paymentMode = $(this).val();
    parseInt(paymentMode);
});

listenSubmit("#clientPaymentForm", function (e) {
    if ($("#payment_note").val().trim().length == 0) {
        displayErrorMessage("Note field is Required");
        return false;
    }

    e.preventDefault();
    let btnSubmitEle = $(this).find("#btnPay");
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
                    // $('#clientPaymentModal').modal('hide');
                    displaySuccessMessage(result.message);
                    livewire.emit("refreshDatatable");
                    livewire.emit("resetPageTable");
                    window.location.href = route("client.invoices.index");
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
        $.post(invoiceStripePaymentUrl, payloadData)
            .done((result) => {
                let sessionId = result.data.sessionId;
                stripe
                    .redirectToCheckout({
                        sessionId: sessionId,
                    })
                    .then(function (result) {
                        $(this).html("Make Payment").removeClass("disabled");
                        manageAjaxErrors(result);
                    });
            })
            .catch((error) => {
                $(this).html("Make Payment").removeClass("disabled");
                manageAjaxErrors(error);
            });
    } else if (paymentMode == 3) {
        $.ajax({
            type: "GET",
            url: route("client.paypal.init"),
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
            url: route("razorpay.init"),
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    $("#clientPaymentModal").modal("hide");
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
                    razorPay.on("payment.failed");
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
                note: payloadData.notes,
            })
        );
    }
});
