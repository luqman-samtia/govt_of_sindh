document.addEventListener('turbo:load', loadCPTransaction);

function loadCPTransaction() {
    initializeSelect2CPTransaction()
}

function initializeSelect2CPTransaction(){
    if(!select2NotExists('#paymentModeFilter')){
        return false;
    }
    removeSelect2Container(["#paymentModeFilter"])
}

listenClick('#resetFilter', function () {
    $('#paymentModeFilter').select2({
        placeholder: 'Select Payment Method',
        allowClear: false,
    });
    $('#paymentModeFilter').val(0).trigger('change');
});
