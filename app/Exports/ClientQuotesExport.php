<?php

namespace App\Exports;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class ClientQuotesExport implements FromView
{
    public function view(): View
    {
        $quotes = Quote::with('client.user')->where('client_id', Auth::user()->client->id)->get();

        return view('excel.client_quotes_excel', compact('quotes'));
    }
}
