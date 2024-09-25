document.addEventListener('turbo:load', createEditProduct);

function createEditProduct() {
    loadSelect2Dropdown()
}

function loadSelect2Dropdown() {
    let categorySelect2 = $('#adminCategoryId');
    if (!categorySelect2.length) {
        return false;
    }

    if ($('#adminCategoryId').hasClass("select2-hidden-accessible")) {
        $('.select2-container').remove();
    }

    $('#adminCategoryId').select2({
        width: '100%',
    });
}

listenChange('#countryId', function () {
    $.ajax({
        url: route('states-list'),
        type: 'get',
        dataType: 'json',
        data: {countryId: $(this).val()},
        success: function (data) {
            $('#stateId').empty()
            $('#stateId').select2({
                placeholder: 'Select State',
                allowClear: false,
            })
            $('#stateId').append(
                $('<option value=""></option>').text('Select State'));
            $.each(data.data, function (i, v) {
                $('#stateId').append($('<option></option>').attr('value', i).text(v));
            });

            if ($('#isEdit').val() && $('#stateId').val()) {
                $('#stateId').val($('#stateId').val()).trigger('change');
            }
        },
    });
});

listenChange('#stateId', function () {
    $.ajax({
        url: route('cities-list'),
        type: 'get',
        dataType: 'json',
        data: {
            stateId: $(this).val(),
            country: $('#countryId').val(),
        },
        success: function (data) {
            $('#cityId').empty()
            $('#cityId').select2({
                placeholder: 'Select City',
                allowClear: false,
            });
            $.each(data.data, function (i, v) {
                $('#cityId').append($('<option></option>').attr('value', i).text(v));
            });

            if ($('#isEdit').val() && $('#cityId').val()) {
                $('#cityId').val($('#cityId').val()).trigger('change');
            }
        },
    });
});

listenClick('.remove-image', function () {
    defaultAvatarImagePreview('#previewImage', 1);
});

listenSubmit('#clientForm, #editClientForm', function () {
    if ($('#error-msg').text() !== '') {
        $('#phoneNumber').focus();
        return false;
    }
});

listenKeyup('#code', function () {
    return $('#code').val(this.value.toUpperCase());
});
listenClick('#autoCode', function () {
    let code = Math.random().toString(36).toUpperCase().substr(2, 6);
    $('#code').val(code);
});

listenClick('.remove-image', function () {
    defaultImagePreview('#previewImage', 1);
});
