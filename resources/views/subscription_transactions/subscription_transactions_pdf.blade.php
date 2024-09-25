<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="d-flex align-items-center justify-content-center mb-4">
        <h4 class="text-center">Subscription Transactions Export Data</h4>
    </div>
    <table class="table table-bordered border-primary">
        <thead>
            <tr>
                <th style="width: 10%"><b>User Name</b></th>
                <th style="width: 15%"><b>Amount</b></th>
                <th style="width: 20%"><b>Transaction Date</b></th>
                <th style="width: 15%"><b>Payment Type</b></th>
                <th style="width: 8%"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            @if (count($subscriptionTransactions) > 0)
                @foreach ($subscriptionTransactions as $transaction)
                    <tr class="custom-font-size-pdf">
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
            @else
                <tr>
                    <td class="text-center" colspan="5">No records found.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
