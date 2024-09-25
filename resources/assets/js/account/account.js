listenClick(".addBankAccount", function () {
    $("#addBankAccountForm")[0].reset();
    $("#addBankAccountModel").appendTo("body").modal("show");
});

listenSubmit("#addBankAccountForm", function (e) {
    e.preventDefault();
    if (isDoubleClicked($(this))) return;

    $.ajax({
        url: route("accounts.store"),
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#addBankAccountModel").modal("hide");
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#bankAccountTbl").DataTable().ajax.reload(null, false);
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

listenClick(".account-edit-btn", function (event) {
    let accountId = $(event.currentTarget).attr("data-id");
    taxRenderData(accountId);
});

function taxRenderData(accountId) {
    $.ajax({
        url: route("accounts.edit", accountId),
        type: "GET",
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#editAccountHolderName").val(result.data.holder_name);
                $("#editBankAccountName").val(result.data.bank_name);
                $("#editAccountNumber").val(result.data.account_number);
                $("#editBalance").val(result.data.balance);
                $("#editAddress").val(result.data.address);
                $("#bankAccountId").val(result.data.id);
                $("#editBankAccountModel").appendTo("body").modal("show");
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

listenSubmit("#editBankAccountForm", function (event) {
    event.preventDefault();
    let accountId = $("#bankAccountId").val();
    $.ajax({
        url: route("accounts.update", accountId),
        type: "put",
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#editBankAccountModel").modal("hide");
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#bankAccountTbl").DataTable().ajax.reload(null, false);
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

listenClick(".account-delete-btn", function (event) {
    let account = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("accounts.destroy", account),
        "#bankAccountTbl",
        Lang.get("messages.accounts.account")
    );
});
