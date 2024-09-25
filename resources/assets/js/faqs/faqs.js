listenSubmit('#addFaqForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#faqSaveBtn')
    loadingButton.button('loading')
    $('#faqSaveBtn').attr('disabled', true)
    $.ajax({
        url: route('faqs.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function success (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addFaqModal').modal('hide');
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#faqSaveBtn').attr('disabled', false);
            }
        },
        error: function error (result) {
            displayErrorMessage(result.responseJSON.message)
            $('#faqSaveBtn').attr('disabled', false)
        },
        complete: function complete () {
            loadingButton.button('reset')
        },
    })

})

listenClick('.faq-edit-btn', function (event) {
    let faqsId = $(event.currentTarget).attr('data-id')
    $.ajax({
        url: route('faqs.edit', faqsId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#faqsId').val(result.data.id)
                $('#editQuestion').val(result.data.question)
                $('#editAnswer').val(result.data.answer)
                $('#editFaqModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
})

listenHiddenBsModal('#addFaqModal', function () {
    resetModalForm('#addFaqForm', '#validationErrorsBox');
});

listenClick('.faq-show-btn', function (event) {
    ajaxCallInProgress()
    let faqsId = $(event.currentTarget).attr('data-id')
    $.ajax({
        url: route('faqs.show', faqsId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#showQuestion').text(result.data.question)
                $('#showAnswer').text(result.data.answer)
                $('#showFaqModal').modal('show')
                livewire.emit('refreshDatatable')
                livewire.emit('resetPageTable')
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
        },
    })
})

listenSubmit('#editFaqForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#faqEditSaveBtn')
    loadingButton.button('loading')
    $('#faqEditSaveBtn').attr('disabled', true)
    let id = $('#faqsId').val()
    $.ajax({
        url: route('faqs-update', id),
        type: 'post',
        data: $(this).serialize(),
        success: function (result) {
            displaySuccessMessage(result.message)
            $('#editFaqModal').modal('hide')
            livewire.emit('refreshDatatable');
            livewire.emit('resetPageTable');
            $('#faqEditSaveBtn').attr('disabled', false)
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
            $('#faqEditSaveBtn').attr('disabled', false)
        },
        complete: function () {
            loadingButton.button('reset')
        },
    })
})

listenClick('.addFaqButton', function () {
    $('#addFaqModal').appendTo('body').modal('show');
})

listenHiddenBsModal('#addFaqModal', function () {
    resetModalForm('#addNewForm', '#addFaqModal #validationErrorsBox')
    $('#faqSaveBtn').attr('disabled', false)
})

listenHiddenBsModal('#editFaqModal', function () {
    resetModalForm('#editFaqForm', '#editFaqModal #editValidationErrorsBox')
    $('#faqEditSaveBtn').attr('disabled', false)
})

listenClick('.faq-delete-btn', function (event) {
    let faqsId = $(event.currentTarget).attr('data-id')
    deleteItem(route('faqs.destroy', faqsId), $('#faqsTable'),
        Lang.get('messages.faqs.faqs'));
})
