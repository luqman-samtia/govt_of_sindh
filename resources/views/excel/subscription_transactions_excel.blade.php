<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subscription Transactions Excel</title>
</head>

<body>
    @php
        $styleCss = 'style';
    @endphp
    <table>
        <thead>
            <tr>
                <th {{ $styleCss }}="width: 300%"><b>User Name</b></th>
                <th {{ $styleCss }}="width: 200%"><b>Amount</b></th>
                <th {{ $styleCss }}="width: 270%"><b>Transaction Date</b></th>
                <th {{ $styleCss }}="width: 180%"><b>Payment Type</b></th>
                <th {{ $styleCss }}="width: 160%"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscriptionTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->user->full_name }}</td>
                    <td>{{ superAdminCurrencyAmount($transaction->amount, false, getAdminSubscriptionPlanCurrencyIcon($transaction->transactionSubscription->subscriptionPlan->currency_id)) }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('jS  M, Y h:i A') }}
                    </td>
                    <td>{{ !empty($transaction->payment_mode) ? \App\Models\Transaction::PAYMENT_TYPES[$transaction->payment_mode] : '' }}
                    </td>
                    @if (
                        $transaction->status == \App\Models\Transaction::PAID ||
                            $transaction->status == 'paid' ||
                            $transaction->status == \App\Models\Transaction::APPROVED)
                        <td>{{ \App\Models\Transaction::PAID }}</td>
                    @elseif($transaction->status == \App\Models\Transaction::DENIED)
                        <td>{{ \App\Models\Transaction::DENIED }}</td>
                    @elseif($transaction->status == \App\Models\Transaction::UNPAID)
                        <td>{{ \App\Models\Transaction::UNPAID }}</td>
                    @elseif($transaction->status == 0)
                        <td>Processing</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

</html>
