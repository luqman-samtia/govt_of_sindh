document.addEventListener('turbo:load', loadSubsPlanCreateEdit);

function loadSubsPlanCreateEdit() {
    $('.price-input').trigger('input')
    $('#createSubscriptionPlanForm, #editSubscriptionPlanForm').
        find('input:text:visible:first').
        focus()
}

listenSubmit('#createSubscriptionPlanForm, #editSubscriptionPlanForm', function () {
    $('#btnSave').attr('disabled', true)
});

