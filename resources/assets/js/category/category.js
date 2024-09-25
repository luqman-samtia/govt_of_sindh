
listenClick('.addCategory', function () {
    $('#addCategoryModal').appendTo('body').modal('show');
});

listenSubmit('#addCategoryForm', function (e) {
    e.preventDefault();
    $.ajax({
        url:route('category.store'),
        type: 'POST',
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $('#addCategoryModal').modal('hide');
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                displaySuccessMessage(result.message);
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

listenHiddenBsModal('#addCategoryModal', function () {
    resetModalForm('#addCategoryForm', '#validationErrorsBox');
});

listenClick('.category-edit-btn', function (event) {
    let categoryId = $(event.currentTarget).attr('data-id');
    categoryRenderData(categoryId);
});

function categoryRenderData(categoryId) {
    $.ajax({
        url:route('category.edit',categoryId),
        type: 'GET',
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $('#editCategoryName').val(result.data.name);
                $('#categoryId').val(result.data.id);
                $('#editCategoryModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
        },
    });
};

listenSubmit('#editCategoryForm', function (event) {
    event.preventDefault();
    const categoryId = $('#categoryId').val();
    $.ajax({
        url: route('category.update', { category: categoryId }),
        type: 'put',
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $('#editCategoryModal').modal('hide');
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                displaySuccessMessage(result.message);
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

listenClick('.category-delete-btn', function (event) {
    let categoryId = $(event.currentTarget).attr('data-id');
    deleteItem(route('category.destroy', categoryId), '#categoryTbl',
        Lang.get('messages.category.category'));
});
