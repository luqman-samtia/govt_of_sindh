let invoiceTableName = "";

let start_date;
let end_date;
let datePicker;
let isPickerApply = false;

document.addEventListener("turbo:load", loadInvoice);

function loadInvoice() {
    initializeSelect2Invoice();
    if ($("#status").val() == "") {
        $("#status_filter").val(5).trigger("change");
    }

    invoiceTableName = "#tblInvoices";
    let uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        let clean_uri = uri.substring(0, uri.indexOf("?"));
        window.history.replaceState({}, document.title, clean_uri);
    }

    invoiceDateWiseFilter();
}

function invoiceDateWiseFilter() {
    datePicker = $("#dateRange");

    if (!datePicker.length) {
        return;
    }

    start_date = moment().subtract(100, "years");
    end_date = moment();

    setDatepickerValue(start_date, end_date);
    const last_month = moment().startOf("month").subtract(1, "days");

    datePicker.daterangepicker(
        {
            startDate: start_date,
            endDate: end_date,
            opens: "left",
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                customRangeLabel: Lang.get("messages.common.custom"),
                applyLabel: Lang.get("messages.common.apply"),
                cancelLabel: Lang.get("messages.common.cancel"),
                fromLabel: Lang.get("messages.common.from"),
                toLabel: Lang.get("messages.common.to"),
                monthNames: [
                    Lang.get("messages.months.jan"),
                    Lang.get("messages.months.feb"),
                    Lang.get("messages.months.mar"),
                    Lang.get("messages.months.apr"),
                    Lang.get("messages.months.may"),
                    Lang.get("messages.months.jun"),
                    Lang.get("messages.months.jul"),
                    Lang.get("messages.months.aug"),
                    Lang.get("messages.months.sep"),
                    Lang.get("messages.months.oct"),
                    Lang.get("messages.months.nov"),
                    Lang.get("messages.months.dec"),
                ],
                daysOfWeek: [
                    Lang.get("messages.weekdays.sun"),
                    Lang.get("messages.weekdays.mon"),
                    Lang.get("messages.weekdays.tue"),
                    Lang.get("messages.weekdays.wed"),
                    Lang.get("messages.weekdays.thu"),
                    Lang.get("messages.weekdays.fri"),
                    Lang.get("messages.weekdays.sat"),
                ],
            },
            ranges: {
                [Lang.get("messages.range.all")]: [
                    moment().subtract(100, "years"),
                    moment(),
                ],
                [Lang.get("messages.range.today")]: [moment(), moment()],
                [Lang.get("messages.range.this_week")]: [
                    moment().startOf("week"),
                    moment().endOf("week"),
                ],
                [Lang.get("messages.range.last_week")]: [
                    moment().startOf("week").subtract(7, "days"),
                    moment().startOf("week").subtract(1, "days"),
                ],
                [Lang.get("messages.range.last_30")]: [
                    moment().subtract(29, "days"),
                    moment(),
                ],
                [Lang.get("messages.range.this_month")]: [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                [Lang.get("messages.range.last_month")]: [
                    last_month.clone().startOf("month"),
                    last_month.clone().endOf("month"),
                ],
            },
        },
        setDatepickerValue
    );

    datePicker.on("apply.daterangepicker", function (ev, picker) {
        isPickerApply = true;
        start_date = picker.startDate.format("YYYY-MM-D");
        end_date = picker.endDate.format("YYYY-MM-D");
        livewire.emit("changeDateFilter", [start_date, end_date]);
    });

    function setDatepickerValue(start, end) {
        datePicker.val(
            start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY")
        );
    }

    listenChange("#invoiceStatusId", function () {
        let status = $(this).val();
        let recurringStatus = $("#invoiceRecurringId").val();
        let startDate = start_date;
        let endDate = end_date;
        window.livewire.emit("filterStatus", status, recurringStatus, [
            startDate,
            endDate,
        ]);
    });

    listenChange("#invoiceRecurringId", function () {
        let recurringStatus = $(this).val();
        let status = $("#invoiceStatusId").val();
        let startDate = start_date;
        let endDate = end_date;
        window.livewire.emit("filterRecurringStatus", recurringStatus, status, [
            startDate,
            endDate,
        ]);
    });
}

function initializeSelect2Invoice() {
    if (!select2NotExists("#status_filter")) {
        return false;
    }
    removeSelect2Container(["#status_filter"]);

    $("#status_filter").select2({
        placeholder: "All",
    });
}

listenClick(".invoice-delete-btn", function (event) {
    event.preventDefault();
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("invoices.destroy", id),
        invoiceTableName,
        Lang.get("messages.invoice.invoice")
    );
});

listenClick("#invoiceResetFilters", function () {
    $("#invoiceStatusId").val("").trigger("change");
    $("#invoiceRecurringId").val("").trigger("change");

    let startDate = moment().subtract(100, "years");
    let endDate = moment();
    window.livewire.emit("changeDateFilter", [startDate, endDate]);
    invoiceDateWiseFilter();
    hideDropdownManually($("#invoiceFilters"), $(".dropdown-menu"));
});

listenClick(".reminder-btn", function () {
    let invoiceId = $(this).data("id");
    $.ajax({
        type: "POST",
        url: route("invoice.payment-reminder", invoiceId),
        beforeSend: function () {
            screenLock();
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
            screenUnLock();
        },
    });
});

listenClick(".update-recurring", function (e) {
    e.preventDefault();
    let invoiceId = $(this).data("id");
    $.ajax({
        type: "POST",
        url: route("update-recurring-status", invoiceId),
        beforeSend: function () {
            screenLock();
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
            screenUnLock();
        },
    });
});
