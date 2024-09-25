listenClick('.super-admin-delete-btn', function (event) {
    let recordId = $(event.currentTarget).attr('data-id')
    deleteItem(route('super-admins.destroy', recordId), 'tableName',
        Lang.get('messages.super_admin.super_admin'));
})

listenSubmit('#superAdminCreateForm, #superAdminEditForm', function (e) {
    e.preventDefault();
    if ($('#error-msg').text() !== '') {
        $('#phoneNumber').focus();
        return false;
    }
    $( "#superAdminEditForm,#superAdminCreateForm" )[0].submit();
});
