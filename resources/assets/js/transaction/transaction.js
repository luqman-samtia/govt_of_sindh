document.addEventListener("turbo:load", loadTransaction);

function loadTransaction() {
    initializeSelect2Transaction();
    loadDateRangePicker("#transactionDateRangePicker");
}

function initializeSelect2Transaction() {
    if (!select2NotExists("#paymentModeFilter")) {
        return false;
    }
    removeSelect2Container(["#paymentModeFilter"]);

    $("#paymentModeFilter").select2({
        placeholder: "Select Payment Method",
        allowClear: false,
    });
}

function loadDateRangePicker(selector) {
    if (!$(selector).length) {
        return false;
    }

    dateRange = $(selector);
    startDate = moment().subtract(100, "years");
    endDate = moment();
    setDatepickerValue(startDate, endDate);
    const lastMonth = moment().startOf("month").subtract(1, "days");

    dateRange.daterangepicker(
        {
            startDate: startDate,
            endDate: endDate,
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
                    lastMonth.clone().startOf("month"),
                    lastMonth.clone().endOf("month"),
                ],
            },
        },
        setDatepickerValue
    );

    function setDatepickerValue(start, end) {
        dateRange.val(
            start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY")
        );
    }

    dateRange.on("apply.daterangepicker", function (ev, picker) {
        startDate = picker.startDate.format("YYYY-MM-D");
        endDate = picker.endDate.format("YYYY-MM-D");
        window.livewire.emit("changeDateRangeFilter", [startDate, endDate]);
    });
}

listenChange(".payment-mode-filter", function () {
    window.livewire.emit("changePaymentModeFilter", $(this).val());
});

listenChange(".payment-status-filter", function () {
    window.livewire.emit("changePaymentStatusFilter", $(this).val());
});

listenClick("#transactionResetFilter", function () {
    $(".payment-mode-filter").val("").trigger("change");
    $(".payment-status-filter").val("").trigger("change");
    hideDropdownManually($("#transactionFilterBtn"), $(".dropdown-menu"));
    let startDate = moment().subtract(100, "years");
    let endDate = moment();
    window.livewire.emit("changeDateRangeFilter", [startDate, endDate]);
    loadDateRangePicker("#transactionDateRangePicker");
});

listenClick(".show-payment-notes", function () {
    let paymentId = $(this).attr("data-id");
    paymentData(paymentId);
});

function paymentData(paymentId) {
    $.ajax({
        url: route("payment-notes.show", paymentId),
        type: "GET",
        success: function (result) {
            if (result.success) {
                let notes = isEmpty(result.data) ? "N/A" : result.data;
                $("#showClientNotesId").text(notes);
                $("#paymentNotesModal").appendTo("body").modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}
