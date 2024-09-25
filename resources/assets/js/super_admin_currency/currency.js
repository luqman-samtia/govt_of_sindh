'use strict';

listenClick('.addAdminCurrency', function () {
    $('#addAdminCurrencyModal').appendTo('body').modal('show');
});

listenSubmit('#addAdminCurrencyForm', function (e) {
    e.preventDefault();
    $.ajax({
        url: route('super.admin.currencies.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $('#addAdminCurrencyModal').modal('hide');
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

listenHiddenBsModal('#addAdminCurrencyModal', function () {
    resetModalForm('#addAdminCurrencyForm', '#validationErrorsBox');
});

listenClick('.admin-currency-edit-btn', function (event) {
    let currencyId = $(event.currentTarget).attr('data-id');
    adminCurrencyRenderData(currencyId);
});

function adminCurrencyRenderData(id){
    $.ajax({
        url: route('super.admin.currencies.edit', id),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editAdminCurrencyName').val(result.data.name);
                $('#editAdminCurrencyIcon').val(result.data.icon);
                $('#editAdminCurrencyCode').val(result.data.code);
                $('#adminCurrencyId').val(result.data.id);
                $('#editAdminCurrencyModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
};

listenSubmit('#editAdminCurrencyForm', function (event) {
    event.preventDefault();
    const id = $('#adminCurrencyId').val();
    $.ajax({
        url: route('super.admin.currencies.update', { currency: id }),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#editAdminCurrencyModal').modal('hide');
                $('#currencyTbl').DataTable().ajax.reload(null, false);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick('.admin-currency-delete-btn', function (event) {
    let currencyId = $(event.currentTarget).data('id');
    deleteItem(route('super.admin.currencies.destroy', currencyId),
        '#currencyTbl',
        Lang.get('messages.currency.currency'));
});
