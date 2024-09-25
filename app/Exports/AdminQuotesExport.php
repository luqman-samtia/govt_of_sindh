<?php

namespace App\Exports;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminQuotesExport implements FromView
{
    public function view(): View
    {
        $quotes = Quote::with('client.user')->get();

        return view('excel.admin_quotes_excel', compact('quotes'));
    }
}
