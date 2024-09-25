<?php

namespace App\Exports;

use App\Models\AdminPayment;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class AdminPaymentsExport implements FromView
{
    public function __construct(public $date)
    {
        $this->date = $date;
    }

    public function view(): View
    {
        $timeEntryDate = explode(' - ', $this->date);
        $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
        $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
        $adminPayments = AdminPayment::where('tenant_id', Auth::user()->tenant_id)->whereBetween('payment_date', [$startDate, $endDate])->with(['invoice.client.user'])->get();

        return view('excel.admin_payments_excel', compact('adminPayments'));
    }
}
