listenChange('.status', function (event) {
    let userId = $(event.currentTarget).attr('data-id')
    updateStatus(userId)
})

function updateStatus(userId) {
    $.ajax({
        url: route('users.status', userId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
            }
        },
    })
}

listenChange('.is-verified', function (event) {
    let userId = $(event.currentTarget).attr('data-id')
    $.ajax({
        url: route('users.verified', userId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                window.livewire.emit('resetPageTable')
            }
        },
    })
})

listenClick('.user-delete-btn', function (event) {
    let recordId = $(event.currentTarget).attr('data-id')
    deleteItem(route('users.destroy', recordId), 'tableName',
        Lang.get('messages.user.user'));
})


listenClick('.user-impersonate', function () {
    let id = $(this).data('id');

    let element = document.createElement('a');
    element.setAttribute('href', route('impersonate', id));
    element.setAttribute('data-turbo', 'false');
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
    $('.user-impersonate').prop('disabled', true);
});
