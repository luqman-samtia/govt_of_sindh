document.addEventListener('turbo:load', loadQuote)

function loadQuote () {
    initializeSelect2Quote()
}

function initializeSelect2Quote () {
    if (!select2NotExists('#status_filter')) {
        return false
    }
    removeSelect2Container(['#status_filter'])

    $('#status_filter').select2({
        placeholder: 'All',
    })

    if ($('#status').val() == '') {
        $('#status_filter').val(5).trigger('change')
    }
}

listenClick('.client-quote-delete-btn', function (event) {
    event.preventDefault();
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(route('client.quotes.destroy', id), 'quote',
        Lang.get('messages.quote.quote'));
})

listenClick('#resetFilter', function () {
    $('#status_filter').val(5).trigger('change')
    $('#status_filter').select2({
        placeholder: 'All',
    })
})
