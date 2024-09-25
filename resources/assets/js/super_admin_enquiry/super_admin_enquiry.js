listenClick('#resetFilter', function () {
    $('#filter_status').val(2).trigger('change')
})

listenClick('.enquiry-delete-btn', function (e) {
    let superAdminEnquiryId = $(e.currentTarget).attr('data-id')
    deleteItem($('#enquiryUrl').val() + '/' + superAdminEnquiryId,
        '#superAdminEnquiriesTable', Lang.get('messages.landing.enquiry'));
})
