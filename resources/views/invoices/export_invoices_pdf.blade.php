<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Invoices PDF</title>
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
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
        <h4 class="text-center">{{ getLogInUser()->hasRole('client') ? 'Client' : '' }} Invoices Export Data</h4>
    </div>
    <table class="table table-bordered border-primary">
        <thead>
            <tr>
                {{-- <th style="width: 3%"><b>Invoice ID</b></th> --}}
                <th style="word-break: break-all;width: 8%"><b>Client Name</b></th>
                <th style="width: 14%"><b>Invoice Date</b></th>
                <th style="width: 12%"><b>Invoice Amount</b></th>
                <th style="width: 14%"><b>Paid Amount</b></th>
                <th style="width: 15%"><b>Due Amount</b></th>
                <th style="white-space: nowrap;width: 20%"><b>Due Date</b></th>
                <th style="width: 2%"><b>Status</b></th>
                <th style="word-break: break-all;width: 4%"><b>Address</b></th>
            </tr>
        </thead>
        <tbody>
            @if (count($invoices) > 0)
                @foreach ($invoices as $invoice)
                    <tr class="custom-font-size-pdf">
                        {{-- <td>{{ $invoice->invoice_id }}</td> --}}
                        <td>{{ $invoice->client?->user->FullName }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
                        </td>
                        <td class="right-align">
                            {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}</td>
                        <td class="right-align">
                            {{ getInvoicePaidAmount($invoice->id) != 0 ? getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) : '0.00' }}
                        </td>
                        @if ($invoice->status == \App\Models\Invoice::DRAFT)
                            <td>N/A</td>
                        @else
                            <td class="right-align">
                                {{ getInvoiceDueAmount($invoice->id) != 0 ? getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) : '0.00' }}
                            </td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}</td>
                        @if ($invoice->status == \App\Models\Invoice::DRAFT)
                            <td> Draft</td>
                        @elseif($invoice->status == \App\Models\Invoice::UNPAID)
                            <td> Unpaid</td>
                        @elseif($invoice->status == \App\Models\Invoice::PAID)
                            <td> Paid</td>
                        @elseif($invoice->status == \App\Models\Invoice::PARTIALLY)
                            <td> Partially Paid</td>
                        @elseif($invoice->status == \App\Models\Invoice::OVERDUE)
                            <td> Overdue</td>
                        @elseif($invoice->status == \App\Models\Invoice::PROCESSING)
                            <td> Processing</td>
                        @endif
                        <td>{{ $invoice->client?->address ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">No records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
