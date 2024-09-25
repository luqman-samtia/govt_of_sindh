<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Payments Excel</title>
</head>
<body>
@php
    $styleCss = 'style';
@endphp
<table>
    <thead>
    <tr>
        <th {{$styleCss}}="width: 170%"><b>Payment Date</b></th>
        <th {{$styleCss}}="width: 170%"><b>Invoice ID</b></th>
        <th {{$styleCss}}="width: 200%"><b>Client Name</b></th>
        <th {{$styleCss}}="width: 200%"><b>Payment Amount</b></th>
        <th {{$styleCss}}="width: 200%"><b>Payment Method</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($adminPayments as $payment)
        <tr>
            <td>{{ Carbon\Carbon::parse($payment->payment_date)->format(currentDateFormat()) }}</td>
            <td>{{ $payment->invoice->invoice_id }}</td>
            <td>{{ $payment->invoice->client->user->full_name }}</td>
            <td>{{ getInvoiceCurrencyAmount($payment->amount, $payment->invoice->currency_id) }}</td>
            @if($payment->payment_mode == \App\Models\Payment::CASH)
                <td> Cash </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
