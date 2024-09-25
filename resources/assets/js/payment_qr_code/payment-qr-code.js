listenClick(".open-payment-qr-code-modal", function () {
    $("#addPaymentQrCodeForm")[0].reset();
    $("#paymentQrCodeInputImage").css(
        "background-image",
        'url("/assets/images/avatar.png")'
    );
    $("#addPaymentQrCodeModal").appendTo("body").modal("show");
});

listenSubmit("#addPaymentQrCodeForm", function (e) {
    e.preventDefault();
    if (isDoubleClicked($(this))) return;

    $.ajax({
        url: route("payment-qr-codes.store"),
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#addPaymentQrCodeModal").modal("hide");
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#paymentQRCodeTbl").DataTable().ajax.reload(null, false);
                // Turbo.visit(route("payment-qr-codes.index"));
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

listenClick(".qrcode-edit-btn", function (event) {
    let paymentQrCodeId = $(event.currentTarget).attr("data-id");
    taxRenderData(paymentQrCodeId);
});

function taxRenderData(paymentQrCodeId) {
    $.ajax({
        url: route("payment-qr-codes.edit", paymentQrCodeId),
        type: "GET",
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#editQrCodeTitle").val(result.data.title);
                $(".qr_code_image").css(
                    "background-image",
                    "url('" + result.data.qr_image + "')"
                );
                $("#paymentQrCodeId").val(result.data.id);
                $("#editPaymentQrCodeModal").appendTo("body").modal("show");
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

listenSubmit("#editPaymentQrCodeForm", function (event) {
    event.preventDefault();
    let paymentQrCodeId = $("#paymentQrCodeId").val();
    $.ajax({
        url: route("payment-update", paymentQrCodeId),
        type: "post",
        data: new FormData(this),
        contentType: false,
        processData: false,
        beforeSend: function () {
            startLoader();
        },

        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#editPaymentQrCodeModal").modal("hide");
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#paymentQRCodeTbl").DataTable().ajax.reload(null, false);
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

listenClick(".qrcode-delete-btn", function (event) {
    let paymentQrCode = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("payment-qr-codes.destroy", paymentQrCode),
        "#paymentQRCodeTbl",
        Lang.get("messages.payment_qr_codes.payment_qr_code")
    );
});

listenChange(".qr-status", function (event) {
    let paymentQrCodeId = $(event.currentTarget).attr("data-id");
    updateStatus(paymentQrCodeId);
});

function updateStatus(paymentQrCodeId) {
    $.ajax({
        url: route("payment-qr-codes.default-status", paymentQrCodeId),
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
            }
        },
    });
}
