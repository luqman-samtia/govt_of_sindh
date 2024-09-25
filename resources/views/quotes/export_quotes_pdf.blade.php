<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Quotes PDF</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .custom-font-size-pdf {
            font-size: 10px !important;
        }

        .table thead th {
            font-size: 11px !important;
        }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center mb-4">
    <h4 class="text-center">{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Quotes Export Data</h4>
</div>
<table class="table table-bordered border-primary">
    <thead>
    <tr>
        <th style="width: 10%"><b>Quote ID</b></th>
        <th style="word-break: break-all;width: 10%"><b>Client Name</b></th>
        <th style="width: 14%"><b>Client Email</b></th>
        <th style="width: 13%"><b>Quote Date</b></th>
        <th style="width: 15%"><b>Amount</b></th>
        <th style="width: 25%"><b>Due Date</b></th>
        <th style="width: 8%"><b>Status</b></th>
        <th style="word-break: break-all;width: 5%"><b>Address</b></th>
    </tr>
    </thead>
    <tbody>
        @if(count($quotes) > 0)
            @foreach($quotes as $quote)
                <tr class="custom-font-size-pdf">
                    <td>{{ $quote->quote_id }}</td>
                    <td>{{ $quote->client->user->FullName }}</td>
                    <td>{{ $quote->client->user->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}</td>
                    <td class="right-align">{{ getCurrencyAmount($quote->final_amount, true) }}</td>
                    <td class="right-align">{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}</td>
                    @if($quote->status == \App\Models\Quote::DRAFT)
                        <td> Draft</td>
                    @elseif($quote->status == \App\Models\Quote::CONVERTED)
                        <td> Converted</td>
                    @endif
                    <td>{{ $quote->client->address ?? 'N/A' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="7">No records found.</td>
            </tr>
        @endif
    </tbody>
</table>
</body>
</html>
