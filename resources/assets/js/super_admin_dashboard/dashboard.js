let start_date
let end_date
let datePicker
let isPickerApply = false

document.addEventListener('turbo:load', loadSuperAdminDashboard)

function loadSuperAdminDashboard() {
    initSuperAdminDashboardDatePicker();
}

function initSuperAdminDashboardDatePicker() {
    datePicker = $('#super_admin_time_range');

    if (!datePicker.length) {
        return;
    }

    start_date = moment().startOf('month')
    end_date = moment().endOf('month');

    setRevenueDatepickerValue(start_date, end_date);
    const last_month = moment().startOf('month').subtract(1, 'days')

    datePicker.daterangepicker({
        maxDate: new Date(),
        startDate: start_date,
        endDate: end_date,
        opens: 'left',
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            customRangeLabel: Lang.get('messages.common.custom'),
            applyLabel:Lang.get('messages.common.apply'),
            cancelLabel: Lang.get('messages.common.cancel'),
            fromLabel:Lang.get('messages.common.from'),
            toLabel: Lang.get('messages.common.to'),
            monthNames: [
                Lang.get('messages.months.jan'),
                Lang.get('messages.months.feb'),
                Lang.get('messages.months.mar'),
                Lang.get('messages.months.apr'),
                Lang.get('messages.months.may'),
                Lang.get('messages.months.jun'),
                Lang.get('messages.months.jul'),
                Lang.get('messages.months.aug'),
                Lang.get('messages.months.sep'),
                Lang.get('messages.months.oct'),
                Lang.get('messages.months.nov'),
                Lang.get('messages.months.dec')
            ],
            daysOfWeek: [
                Lang.get('messages.weekdays.sun'),
                Lang.get('messages.weekdays.mon'),
                Lang.get('messages.weekdays.tue'),
                Lang.get('messages.weekdays.wed'),
                Lang.get('messages.weekdays.thu'),
                Lang.get('messages.weekdays.fri'),
                Lang.get('messages.weekdays.sat')
            ],
        },
        ranges: {
            [ Lang.get('messages.range.today')]: [moment(), moment()],
            [ Lang.get('messages.range.this_week')]: [moment().startOf('week'), moment().endOf('week')],
            [ Lang.get('messages.range.last_week')]: [
                moment().startOf('week').subtract(7, 'days'),
                moment().startOf('week').subtract(1, 'days')],
            [ Lang.get('messages.range.last_30')]: [start_date, end_date],
            [ Lang.get('messages.range.this_month')]: [moment().startOf('month'), moment().endOf('month')],
            [ Lang.get('messages.range.last_month')]: [
                last_month.clone().startOf('month'),
                last_month.clone().endOf('month')],
        },
    }, setRevenueDatepickerValue);

    loadRevenueChart(start_date.format('YYYY-MM-D'), end_date.format('YYYY-MM-D'));

    datePicker.on('apply.daterangepicker', function (ev, picker) {
        isPickerApply = true
        start_date = picker.startDate.format('YYYY-MM-D')
        end_date = picker.endDate.format('YYYY-MM-D');
        loadRevenueChart(start_date, end_date);
    });
}

function setRevenueDatepickerValue(start, end) {
    datePicker.val(start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY"))
}

function loadRevenueChart(startDate, endDate) {
    $.ajax({
        type: 'GET',
        url: route('super-admin.revenue-chart'),
        dataType: 'json',
        data: {
            start_date: startDate,
            end_date: endDate,
        },
        cache: false,
    }).done(prepareRevenueChart);
}


function prepareRevenueChart(result) {
    $('#revenue_overview-container').html('');
    let data = result.data;
    if (data.total_records === 0) {
        $('#revenue_overview-container').empty();
        $('#revenue_overview-container').append(
            '<div align="center" class="no-record justify-align-center">' +
            Lang.get('messages.admin_dashboard.no_record_found') +
            '</div>')
        return true;
    } else {
        $('#revenue_overview-container').html('');
        $('#revenue_overview-container').append(
            '<canvas id="revenue_chart_canvas" height="200"></canvas>');
    }
    let ctx = document.getElementById('revenue_chart_canvas').getContext('2d');
    ctx.canvas.style.height = '500px';
    ctx.canvas.style.width = '908px';

    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: data.month, // Name the series
                    data: data.yearly_revenue, // Specify the data values array
                    fill: false,
                    borderColor: '#2196f3', // Add custom color border (Line)
                    backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                    borderWidth: 2, // Specify bar border width
                }],
        },
        options: {
            elements: {
                line: {
                    tension: 0.5,
                },
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return currencyAmount(context.formattedValue);
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false,
                    },
                    ticks: {
                        min: 0,
                        // stepSize: 500,
                        callback: function (label) {
                            return currencyAmount(label);
                        },
                    },
                },
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false,
                    },
                },
            },
        },
    });
}
