<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Transactions Excel</title>
</head>

<body>
    @php
        $styleCss = 'style';
    @endphp
    <table>
        <thead>
            <tr>
                <th {{ $styleCss }}="width: 200%"><b>Payment Date</b></th>
                <th {{ $styleCss }}="width: 170%"><b>Invoice ID</b></th>
                <th {{ $styleCss }}="width: 180%"><b>Client Name</b></th>
                <th {{ $styleCss }}="width: 180%"><b>Payment Amount</b></th>
                <th {{ $styleCss }}="width: 200%"><b>Payment Approved</b></th>
                <th {{ $styleCss }}="width: 160%"><b>Payment Method</b></th>
                <th {{ $styleCss }}="width: 160%"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat(currentDateFormat()) }}
                        {{ \Carbon\Carbon::parse($payment->payment_date)->isoFormat('hh:mm A') }}</td>
                    <td>{{ $payment->invoice->invoice_id }}</td>
                    <td>{{ $payment->invoice->client->user->full_name }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ \App\Models\Payment::PAYMENT_APPROVE[$payment->is_approved] }}</td>
                    <td>{{ !empty($payment->payment_mode) ? \App\Models\Payment::PAYMENT_MODE[$payment->payment_mode] : '' }}
                    </td>
                    @if ($payment->is_approved == \App\Models\Payment::APPROVED)
                        <td> Paid</td>
                    @elseif($payment->is_approved == \App\Models\Payment::PENDING)
                        <td> Processing</td>
                    @elseif($payment->is_approved == \App\Models\Payment::REJECTED)
                        <td> Denied</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

</html>
