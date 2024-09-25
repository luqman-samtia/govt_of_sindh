document.addEventListener('turbo:load', loadInvoiceTemplates)

let pickr = null;

function loadInvoiceTemplates() {
    initializeSelect2();
    loadPickrData();

    if (pickr !== null) {
        let color = $('#invoiceColor').val();
        if(color !=null)
        {
        pickr.setColor(color);
        let template = $('#invoiceTemplateId').val();
        let invoiceData = [
            {
                'invColor': color,
                'companyName': $('#companyName').val(),
                'companyAddress': $('#companyAddress').val(),
                'companyPhone': $('#companyPhoneNumber').val(),
            }];
            let value = prepareTemplateRender('#' + template, invoiceData);
            $('#editorContent').html(value);
        }
    }

    if ($('#invoiceTemplateId').length) {
        let template = $('#invoiceTemplateId').val();
        let color = $('#invoiceTemplateId').select2().find(":selected").data('color');
        prepareDefaultTemplate(template, color);
    }
}

function initializeSelect2() {
    let invoiceTemplateId = $('#invoiceTemplateId');

    if (!invoiceTemplateId.length) {
        return;
    }
    $('#invoiceTemplateId').select2({
        width: '100%',
    });
}

// Invoice Template JS For Setting Module
function loadPickrData() {
    let colorPickerSelector = $('.color-wrapper');
    if (!colorPickerSelector.length) {
        return;
    }
    pickr = Pickr.create({
        el: '.color-wrapper',
        theme: 'nano', // or 'monolith', or 'nano'
        closeWithKey: 'Enter',
        autoReposition: true,
        defaultRepresentation: 'HEX',
        swatches: [
            'rgba(244, 67, 54, 1)',
            'rgba(233, 30, 99, 1)',
            'rgba(156, 39, 176, 1)',
            'rgba(103, 58, 183, 1)',
            'rgba(63, 81, 181, 1)',
            'rgba(33, 150, 243, 1)',
            'rgba(3, 169, 244, 1)',
            'rgba(0, 188, 212, 1)',
            'rgba(0, 150, 136, 1)',
            'rgba(76, 175, 80, 1)',
            'rgba(139, 195, 74, 1)',
            'rgba(205, 220, 57, 1)',
            'rgba(255, 235, 59, 1)',
            'rgba(255, 193, 7, 1)',
        ],

        components: {
            // Main components
            preview: true,
            hue: true,

            // Input / output Options
            interaction: {
                input: true,
                clear: false,
                save: false,
            },
        },
    });

    pickr.on('change', function () {
        let color = pickr.getColor().toHEXA().toString();

        if (wc_hex_is_light(color)) {
            $('#validationErrorsBox').text('');
            $('#validationErrorsBox').show().html('');
            $('#validationErrorsBox').text('Pick a different color');
            setTimeout(function () {
                $('#validationErrorsBox').slideUp();
            }, 5000);
            $(':input[id="btnSave"]').prop('disabled', true);
            return;
        }
        $(':input[id="btnSave"]').prop('disabled', false);
        pickr.setColor(color);
        $('#invoiceColor').val(color);
        let template = $('#invoiceTemplateId').val();
        let invoiceData = [
            {
                'invColor': color,
                'companyName': $('#companyName').val(),
                'companyAddress':  $('#companyAddress').val(),
                'companyPhone': $('#companyPhoneNumber').val(),
            }];
        let value = prepareTemplateRender('#' + template, invoiceData);
        $('#editorContent').html(value);
    });
}

listenChange('#invoiceTemplateId', function () {
    let template = $(this).val();
    let color = $(this).select2().find(":selected").data('color');

    prepareDefaultTemplate(template, color);
});

function prepareDefaultTemplate(template, color) {
    let invoiceData = [
        {
            'invColor': color,
            'companyName': $('#companyName').val(),
            'companyAddress': $('#companyAddress').val(),
            'companyPhone': $('#companyPhoneNumber').val(),
        }];

    let value = prepareTemplateRender('#' + template, invoiceData);
    $('#invoiceColor').val(color);
    $('#editorContent').html(value);

    setTimeout(function () {
        pickr.setColor(color);
    }, 200);
}
