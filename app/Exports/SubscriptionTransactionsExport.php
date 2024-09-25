<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SubscriptionTransactionsExport implements FromView
{
    public function view(): View
    {
        $subscriptionTransactions = Transaction::with(['transactionSubscription.subscriptionPlan','user'])->whereHas('transactionSubscription')->orderBy('created_at', 'desc')->get();

        return view('excel.subscription_transactions_excel', compact('subscriptionTransactions'));
    }
}
