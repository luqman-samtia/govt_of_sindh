listenClick('.client-delete-btn', function (event) {
    let recordId = $(event.currentTarget).attr('data-id');

           var callFunction =
        arguments.length > 3 && arguments3 !== undefined ? arguments3 : null;
        header = Lang.get('messages.client.client');
    swal({
        title: Lang.get("messages.common.delete") + " !",
        text:
            Lang.get("messages.common.are_you_sure_delete") +
            ' "' +
            header +
            '" ?',
        buttons: [
            Lang.get("messages.common.no_cancel"),
            Lang.get("messages.common.yes_delete"),
        ],
        icon: sweetAlertIcon,
    }).then(function (willDelete) {
        if (willDelete) {
            $.ajax({
                url: route('clients.destroy', recordId),
                type: "DELETE",
                dataType: "json",
                data: { clientWithInvoices: true },
                success: function (obj) {
                    if (obj.success) {
                        window.livewire.emit("refreshDatatable");
                        window.livewire.emit("resetPageTable");
                    }
                    swal({
                        icon: "success",
                        title: Lang.get("messages.common.deleted"),
                        text:
                            header +
                            " " +
                            Lang.get("messages.common.has_been_deleted"),
                        timer: 2000,
                        button: Lang.get("messages.common.ok"),
                    });
                    if (callFunction) {
                        eval(callFunction);
                    }
                },
                error: function (data) {
                    swal({
                        title: Lang.get("messages.common.delete") + " !",
                        text:
                            Lang.get("messages.flash.are_sure_want_to_delete_this_client_related_all_invoices"),
                        buttons: [
                            Lang.get("messages.common.no_cancel"),
                            Lang.get("messages.common.yes_delete"),
                        ],
                        icon: sweetAlertIcon,
                    }).then(function (willDelete) {
                        if (willDelete) {
                            $.ajax({
                                url: route('clients.destroy', recordId),
                                type: "DELETE",
                                dataType: "json",
                                success: function (obj) {
                                    if (obj.success) {
                                        window.livewire.emit(
                                            "refreshDatatable"
                                        );
                                        window.livewire.emit("resetPageTable");
                                    }
                                    swal({
                                        icon: "success",
                                        title: Lang.get(
                                            "messages.common.deleted"
                                        ),
                                        text:
                                            header +
                                            " " +
                                            Lang.get(
                                                "messages.common.has_been_deleted"
                                            ),
                                        timer: 2000,
                                        button: Lang.get("messages.common.ok"),
                                    });
                                    if (callFunction) {
                                        eval(callFunction);
                                    }
                                },
                                error: function (data) {},
                            });
                        }
                    });
                },
            });
        }
    });

});
