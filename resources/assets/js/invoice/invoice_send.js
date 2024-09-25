listenClick(".send-btn", function (event) {
    let invoiceId = $(event.currentTarget).data("id");
    let status = 1;
    swal({
        title: Lang.get("messages.common.send_invoice") + " !",
        text: Lang.get("messages.common.are_you_sure_send"),
        icon: "warning",
        buttons: [
            Lang.get("messages.common.no_cancel"),
            Lang.get("messages.common.yes_send"),
        ],
    }).then(function (willSend) {
        if (willSend) {
            changeInvoiceStatus(invoiceId, status);
        }
    });
});

function changeInvoiceStatus(invoiceId, status) {
    $.ajax({
        url: route("send-invoice", { invoice: invoiceId, status: status }),
        type: "post",
        dataType: "json",
        success: function (obj) {
            if (obj.success) {
                window.location.reload();
            }
            swal.fire({
                icon: "success",
                title: "Send!",
                confirmButtonColor: "#009ef7",
                text: header + " " + Lang.get("messages.common.successfully"),
                timer: 2000,
            });
        },
        error: function (data) {
            swal.fire({
                title: "",
                text: data.responseJSON.message,
                confirmButtonColor: "#009ef7",
                icon: "error",
                timer: 5000,
            });
        },
    });
}
