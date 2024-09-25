listenClick(".addTax", function () {
    $("#addTaxModal").appendTo("body").modal("show");
});

listenSubmit("#addTaxForm", function (e) {
    e.preventDefault();
    if (isDoubleClicked($(this))) return;

    $.ajax({
        url: route("taxes.store"),
        type: "POST",
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#addTaxModal").modal("hide");
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#taxTbl").DataTable().ajax.reload(null, false);
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

listenHiddenBsModal("#addTaxModal", function () {
    resetModalForm("#addTaxForm", "#validationErrorsBox");
});

listenClick(".tax-edit-btn", function (event) {
    let taxId = $(event.currentTarget).attr("data-id");
    taxRenderData(taxId);
});

listenSubmit("#editTaxForm", function (event) {
    event.preventDefault();
    const taxId = $("#taxId").val();
    $.ajax({
        url: route("taxes.update", { tax: taxId }),
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
                $("#editTaxModal").modal("hide");
                $("#taxTbl").DataTable().ajax.reload(null, false);
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

listenClick(".tax-delete-btn", function (event) {
    let taxId = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("taxes.destroy", taxId),
        "#taxTbl",
        Lang.get("messages.invoice.tax")
    );
});

listenChange(".tax-status", function (event) {
    let taxId = $(event.currentTarget).attr("data-id");
    updateStatus(taxId);
});

function taxRenderData(taxId) {
    $.ajax({
        url: route("taxes.edit", taxId),
        type: "GET",
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#editTaxName").val(result.data.name);
                $("#editTaxValue").val(result.data.value);
                if (result.data.is_default === 1) {
                    $("input:radio[value='1'][name='is_default']").prop(
                        "checked",
                        true
                    );
                } else {
                    $("input:radio[value='0'][name='is_default']").prop(
                        "checked",
                        true
                    );
                }
                $("#taxId").val(result.data.id);
                $("#editTaxModal").appendTo("body").modal("show");
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

function updateStatus(taxId) {
    $.ajax({
        url: route("taxes.default-status", taxId),
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            livewire.emit("refreshDatatable");
            livewire.emit("resetPageTable");
        },
    });
}
