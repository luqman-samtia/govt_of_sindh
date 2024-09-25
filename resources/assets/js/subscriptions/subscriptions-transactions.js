listenClick('#resetFilter', function () {
    $('#paymentTypeArr').val('').trigger('change');
});

listenChange('.payment-approve', function () {
    let id = $(this).attr('data-id');
    let status = $(this).val();

    $.ajax({
        url: route('change-payment-status', id),
        type: 'GET',
        data: {id: id, status: status},
        success: function (result) {
            displaySuccessMessage(result.message);
            livewire.emit('refreshDatatable');
            livewire.emit('resetPageTable');
            Turbo.visit(route('subscriptions.transactions.index'));
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});
