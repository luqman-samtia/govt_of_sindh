document.addEventListener("turbo:load", loadSubsSubscription);

function loadSubsSubscription() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    if ($("#paymentType").length) {
        activatePaymentButton($("#paymentType").val());
        $("#paymentType").trigger("change");
    }
}

listenClick(".makePayment", function () {
    if (
        typeof getLoggedInUserdata != "undefined" &&
        getLoggedInUserdata == ""
    ) {
        window.location.href = logInUrl;

        return true;
    }

    let payloadData = {
        plan_id: $(this).data("id"),
        from_pricing: typeof fromPricing != "undefined" ? fromPricing : null,
        price: $(this).data("plan-price"),
        payment_type: $("#paymentType option:selected").val(),
    };
    $(this).addClass("disabled");
    $.post(makePaymentURL, payloadData)
        .done((result) => {
            if (typeof result.data == "undefined") {
                let toastMessageData = {
                    toastType: "success",
                    toastMessage: result.message,
                };
                paymentMessage(toastMessageData);
                setTimeout(function () {
                    window.location.href = subscriptionPlans;
                }, 5000);

                return true;
            }

            let sessionId = result.data.sessionId;
            stripe
                .redirectToCheckout({
                    sessionId: sessionId,
                })
                .then(function (result) {
                    $(this).html(subscribeText).removeClass("disabled");
                    $(".makePayment").attr("disabled", false);
                    let toastMessageData = {
                        toastType: "error",
                        toastMessage: result.responseJSON.message,
                    };
                    paymentMessage(toastMessageData);
                });
        })
        .catch((error) => {
            $(this).html(subscribeText).removeClass("disabled");
            $(".makePayment").attr("disabled", false);
            let toastMessageData = {
                toastType: "error",
                toastMessage: error.responseJSON.message,
            };
            paymentMessage(toastMessageData);
        });
});

listenChange("#paymentType", function () {
    let paymentType = $(this).val();

    if (paymentType == 4) {
        $(".payment-attachments").removeClass("d-none");
    } else {
        $(".payment-attachments").addClass("d-none");
    }

    activatePaymentButton(paymentType);
});

function activatePaymentButton(paymentType) {
    if (paymentType == 1) {
        $(
            ".proceed-to-payment, .razorPayPayment, .cashPayment, .paystackPayPayment, .paypalPayment"
        ).addClass("d-none");
        $(".stripePayment").removeClass("d-none");
    }
    if (paymentType == 2) {
        $(
            ".proceed-to-payment, .razorPayPayment, .cashPayment, .paystackPayPayment"
        ).addClass("d-none");
        $(".paypalPayment").removeClass("d-none");
    }
    if (paymentType == 3) {
        $(
            ".proceed-to-payment, .paypalPayment, .cashPayment, .paystackPayPayment"
        ).addClass("d-none");
        $(".razorPayPayment").removeClass("d-none");
    }
    if (paymentType == 4) {
        $(
            ".proceed-to-payment, .paypalPayment, .razorPayPayment, .paystackPayPayment"
        ).addClass("d-none");
        $(".cashPayment").removeClass("d-none");
    }
    if (paymentType == 5) {
        $(
            ".proceed-to-payment, .paypalPayment, .razorPayPayment, .cashPayment"
        ).addClass("d-none");
        $(".paystackPayPayment").removeClass("d-none");
    }
}

listenClick(".paymentByPaypal", function () {
    let pricing = typeof fromPricing != "undefined" ? fromPricing : null;
    $(this).addClass("disabled");
    $.ajax({
        type: "GET",
        url: route("admin.paypal.init"),
        data: {
            planId: $(this).data("id"),
            from_pricing: pricing,
            payment_type: $("#paymentType option:selected").val(),
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
        error: function (result) {},
        complete: function () {},
    });
});

// RazorPay Payment
listenClick(".razor_pay_payment", function () {
    $(this).addClass("disabled");
    $.ajax({
        type: "POST",
        url: makeRazorpayURl,
        data: {
            plan_id: $(this).data("id"),
            from_pricing:
                typeof fromPricing != "undefined" ? fromPricing : null,
        },
        success: function (result) {
            if (result.url) {
                window.location.href = result.url;
            }
            if (result.success) {
                let { id, amount, name, email, contact, planID } = result.data;
                options.amount = amount;
                options.order_id = id;
                options.prefill.name = name;
                options.prefill.email = email;
                options.prefill.contact = contact;
                options.prefill.planID = planID;
                let razorPay = new Razorpay(options);
                razorPay.open();
                razorPay.on("payment.failed", storeFailedPayment);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {},
    });
});

// Paystack Payment
listenClick(".paystack-pay-payment", function () {
    $(this).addClass("disabled");
    window.location.replace(
        route("paystack.init", {
            planId: $(this).data("id"),
            from_pricing:
                typeof fromPricing != "undefined" ? fromPricing : null,
        })
    );
});

function storeFailedPayment(response) {
    $.ajax({
        type: "POST",
        url: razorpayPaymentFailed,
        data: {
            data: response,
        },
        success: function (result) {
            if (result.url) {
                window.location.href = result.url;
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

// cash Payment
listenClick(".cash_payment", function () {
    $(this).addClass("disabled");
    let formData = new FormData();
    let fileData = $('input[type="file"]')[0].files; // for multiple files
    for (let i = 0; i < fileData.length; i++) {
        formData.append("payment_attachments[]", fileData[i]);
    }

    formData.append("plan_id", $(this).data("id"));
    formData.append(
        "from_pricing",
        typeof fromPricing != "undefined" ? fromPricing : null
    );

    $.ajax({
        type: "POST",
        url: cashPaymentUrl,
        data: formData,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.url) {
                window.location.href = result.url;
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {},
    });
});
