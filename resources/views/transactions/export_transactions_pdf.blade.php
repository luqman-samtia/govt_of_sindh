<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Transactions PDF</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .custom-font-size-pdf {
            font-size: 11px !important;
        }

        .table thead th {
            font-size: 13px !important;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center mb-4">
        <h4 class="text-center">{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Transactions Export Data</h4>
    </div>
    <table class="table table-bordered border-primary">
        <thead>
            <tr>
                @if (getLogInUser()->hasRole('admin'))
                    <th style="width: 13%"><b>Transaction ID</b></th>
                @endif
                <th style="width: 10%"><b>Payment Date</b></th>
                <th style="width: 12%"><b>Invoice ID</b></th>
                @if (getLogInUser()->hasRole('admin'))
                    <th style="width: 20%"><b>Client Name</b></th>
                @endif
                <th style="width: 20%"><b>Payment Amount</b></th>
                <th style="width: 15%"><b>Payment Method</b></th>
                <th style="width: 10%"><b>Payment Status</b></th>
            </tr>
        </thead>
        <tbody>
            @if (count($payments) > 0)
                @foreach ($payments as $payment)
                    <tr class="custom-font-size-pdf">
                        @if (getLogInUser()->hasRole('admin'))
                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat(currentDateFormat()) }}
                            {{ \Carbon\Carbon::parse($payment->payment_date)->isoFormat('hh:mm A') }}</td>
                        <td>{{ $payment->invoice->invoice_id }}</td>
                        @if (getLogInUser()->hasRole('admin'))
                            <td>{{ $payment->invoice->client->user->full_name }}</td>
                        @endif
                        <td class="right-align">
                            {{ getInvoiceCurrencyAmount($payment->amount, $payment->invoice->currency_id, true) }}</td>
                        <td>{{ !empty($payment->payment_mode) ? \App\Models\Payment::PAYMENT_MODE[$payment->payment_mode] : '' }}
                        </td>
                        @if ($payment->is_approved == \App\Models\Payment::APPROVED && $payment->payment_mode == 1)
                            <td>{{ \App\Models\Payment::PAID }}</td>
                        @elseif($payment->is_approved == \App\Models\Payment::PENDING && $payment->payment_mode == 1)
                            <td>{{ \App\Models\Payment::PROCESSING }}</td>
                        @elseif($payment->is_approved == \App\Models\Payment::REJECTED && $payment->payment_mode == 1)
                            <td>{{ \App\Models\Payment::DENIED }}</td>
                        @else
                            <td>{{ \App\Models\Payment::PAID }}</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="{{ getLogInUser()->hasRole('admin') ? 8 : 5 }}">No records found.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
