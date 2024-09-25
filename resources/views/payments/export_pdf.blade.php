<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Payments PDF</title>
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .custom-font-size-pdf {
            font-size: 11px !important;
        }

        .table thead th {
            font-size: 12px !important;
        }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center mb-4">
    <h4 class="text-center">Payments Export Data</h4>
</div>
<table class="table table-bordered border-primary">
    <thead>
    <tr>
        <th style="width: 18%"><b>Payment Date</b></th>
        <th style="width: 15%"><b>Invoice ID</b></th>
        <th style="word-break: break-all;width: 20%"><b>Client Name</b></th>
        <th style="width: 27%"><b>Payment Amount</b></th>
        <th style="width: 20%"><b>Payment Method</b></th>
    </tr>
    </thead>
    <tbody>
    @if(count($adminPayments) > 0)
        @foreach($adminPayments as $payment)
            <tr class="custom-font-size-pdf">
                <td>{{ Carbon\Carbon::parse($payment->payment_date)->format(currentDateFormat()) }}</td>
                <td>{{ $payment->invoice->invoice_id }}</td>
                <td>{{ $payment->invoice->client->user->full_name }}</td>
                <td style="text-align: right">{{ getInvoiceCurrencyAmount($payment->amount, $payment->invoice->currency_id) }}</td>
                @if($payment->payment_mode == \App\Models\Payment::CASH)
                    <td> Cash </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center">No records found.</td>
        </tr>
    @endif
    </tbody>
</table>
</body>
</html>
