<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Transaction Excel</title>

</head>

<body>
    @php
        $styleCss = 'style';
    @endphp
    <table>
        <thead>
            <tr>
                <th {{ $styleCss }}="width: 250%"><b>Payment Date</b></th>
                <th {{ $styleCss }}="width: 170%"><b>Invoice ID</b></th>
                <th {{ $styleCss }}="width: 180%"><b>Payment Amount</b></th>
                <th {{ $styleCss }}="width: 160%"><b>Payment Method</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaction->payment_date)->translatedFormat(currentDateFormat()) }}
                        {{ \Carbon\Carbon::parse($transaction->payment_date)->isoFormat('hh:mm A') }}</td>
                    <td>{{ $transaction->invoice->invoice_id }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ !empty($transaction->payment_mode) ? \App\Models\Payment::PAYMENT_MODE[$transaction->payment_mode] : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
