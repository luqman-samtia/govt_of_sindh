// 'use strict'

listenSubmit('#addTestimonialForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#testimonialSaveBtn')
    loadingButton.button('loading')
    let formData = new FormData($(this)[0])
    $('#testimonialSaveBtn').attr('disabled', true)
    $.ajax({
        url: route('admin-testimonial.store'),
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function success (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#addTestimonialModal').modal('hide')
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#testimonialSaveBtn').attr('disabled', false)
            }
        },
        error: function error (result) {
            displayErrorMessage(result.responseJSON.message)
            $('#testimonialSaveBtn').attr('disabled', false)
        },
        complete: function complete () {
            loadingButton.button('reset')
        },
    });

});

listenClick('.testimonial-show-btn', function () {
    let testimonialShowId = $(this).attr('data-id')
    $.ajax({
        url: route('admin-testimonial.show', testimonialShowId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let ext = result.data.image_url.split('.').
                    pop().
                    toLowerCase()
                if (ext == '') {
                    $('#showPreviewImage').attr('src',result.data.image_url);
                } else {
                    $('#showPreviewImage').attr('src',result.data.image_url);
                }
                $('.show-name').text(result.data.name)
                $('.show-position').text(result.data.position)
                $('.show-description').text(result.data.description)
                if (isEmpty(result.data.document_url)) {
                    $('#documentUrl').hide()
                    $('.btn-view').hide()
                } else {
                    $('#documentUrl').show()
                    $('.btn-view').show()
                    $('#documentUrl').attr('href', result.data.document_url)
                }
                $('#showTestimonialModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
        },
    })
});
//
listenClick('.testimonial-edit-btn', function (event) {
    if (ajaxCallIsRunning) {
        return
    }
    ajaxCallInProgress()
    let testimonialId = $(event.currentTarget).data('id')
    testimonialRenderData(testimonialId)
})
//
function testimonialRenderData(testimonialId) {
    $.ajax({
        url: route('admin-testimonial.edit', testimonialId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let ext = result.data.image_url.split('.').
                    pop().
                    toLowerCase()
                if (ext == '') {
                    $('#editPreviewImage').
                        css('background-image',
                            'url("' + result.data.image_url + '")')
                } else {
                    $('#editPreviewImage').
                        css('background-image',
                            'url("' + result.data.image_url + '")')
                }
                $('#testimonialId').val(result.data.id)
                $('#editName').val(result.data.name)
                $('#editPosition').val(result.data.position)
                $('#editDescription').val(result.data.description)
                if (isEmpty(result.data.document_url)) {
                    $('#documentUrl').hide()
                    $('.btn-view').hide()
                } else {
                    $('#documentUrl').show()
                    $('.btn-view').show()
                    $('#documentUrl').attr('href', result.data.document_url)
                }
                $('#editTestimonialModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
        },
    })
}

listenSubmit('#editTestimonialForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditSave')
    loadingButton.button('loading')
    $('#btnEditSave').attr('disabled', true)
    let id = $('#testimonialId').val()
    let formData = new FormData($(this)[0])
    $.ajax({
        url: route('admin-testimonial.update', id),
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#editTestimonialModal').modal('hide')
                livewire.emit('refreshDatatable');
                livewire.emit('resetPageTable');
                $('#btnEditSave').attr('disabled', false)
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
            $('#btnEditSave').attr('disabled', false)
        },
        complete: function () {
            loadingButton.button('reset')
        },
    })
});
listenClick('.addTestimonialButton', function () {
    $('#addTestimonialModal').appendTo('body').modal('show')
})

listenHiddenBsModal('#addTestimonialModal', function () {
    resetModalForm('#addTestimonialForm', '#addTestimonialModal #validationErrorsBox')
    $('#previewImage').
        attr('src', $('#defaultDocumentImageUrl').val()).
        css('background-image', `url(${$('#defaultDocumentImageUrl').val()})`)
    $('#testimonialSaveBtn').attr('disabled', false)
})

listenShowBsModal('#addTestimonialModal', function () {
    $('#addTestimonialModal #validationErrorsBox').show()
    $('#addTestimonialModal #validationErrorsBox').addClass('d-none')
})
//
listenHiddenBsModal('#editTestimonialModal', function () {
    resetModalForm('#editTestimonialForm', '#editTestimonialModal #editValidationErrorsBox')
    $('#previewImage').
        attr('src', $('#defaultDocumentImageUrl').val()).
        css('background-image', `url(${$('#defaultDocumentImageUrl').val()})`)
    $('#btnEditSave').attr('disabled', false)
})
//
listenShowBsModal('#editTestimonialModal', function () {
    $('#editTestimonialModal #editValidationErrorsBox').show()
    $('#editTestimonialModal #editValidationErrorsBox').addClass('d-none')
})

listenClick('.testimonial-delete-btn', function (event) {
    let testimonialId = $(event.currentTarget).attr('data-id')
    deleteItem(route('admin-testimonial.destroy', testimonialId),
        $('#AdminTestimonialTbl'), Lang.get('messages.testimonial.testimonial'))
})
//
listenChange('#profile', function () {
    let extension = isValidDocument($(this), '#addTestimonialModal #validationErrorsBox')
    if (!isEmpty(extension) && extension != false) {
        displayDocument(this, '#previewImage', extension)
    }
})

listenChange('#editProfile', function () {
    let extension = isValidDocument($(this),
        '#editTestimonialModal #editValidationErrorsBox')
    if (!isEmpty(extension) && extension != false) {
        displayDocument(this, '#editPreviewImage', extension)
    }
})

window.isValidDocument = function (
    inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split('.').pop().toLowerCase()
    if ($.inArray(ext, ['png', 'jpg', 'jpeg']) ==
        -1) {
        $(inputSelector).val('')
        $(validationMessageSelector).
            html(profileError).
            removeClass('d-none')
        return false
    }
    $(validationMessageSelector).
        html(profileError).
        addClass('d-none')
    return ext
}
