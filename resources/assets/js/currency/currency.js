listenClick('.addCurrency', function () {
    $('#addCurrencyModal').appendTo('body').modal('show');
});

listenSubmit('#addCurrencyForm', function (e) {
    e.preventDefault();
    $.ajax({
        url: route('currencies.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $('#addCurrencyModal').modal('hide');
                displaySuccessMessage(result.message);
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#currencyTbl').DataTable().ajax.reload(null, false);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenHiddenBsModal('#addCurrencyModal', function () {
    resetModalForm('#addCurrencyForm', '#validationErrorsBox');
});

listenClick('.currency-edit-btn', function (event) {
    let currencyId = $(event.currentTarget).attr('data-id');
    currencyRenderData(currencyId);
});

function currencyRenderData(currencyId) {
    $.ajax({
        url: route('currencies.edit', currencyId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editCurrencyName').val(result.data.name);
                $('#editCurrencyIcon').val(result.data.icon);
                $('#editCurrencyCode').val(result.data.code);
                $('#currencyId').val(result.data.id);
                $('#editCurrencyModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
};

listenSubmit('#editCurrencyForm', function (event) {
    event.preventDefault();
    const id = $('#currencyId').val();
    $.ajax({
        url: route('currencies.update', {currency: id}),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#editCurrencyModal').modal('hide');
                $('#currencyTbl').DataTable().ajax.reload(null, false);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});
listenClick('.currency-delete-btn', function (event) {
    let currencyId = $(event.currentTarget).attr('data-id');
    deleteItem(route('currencies.destroy', currencyId), '#currencyTbl',
        Lang.get('messages.currency.currency'));
});
