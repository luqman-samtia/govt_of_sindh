<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Quote Excel</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="width: 200%"><b>Quote ID</b></th>
        <th style="width: 150%"><b>Quote Date</b></th>
        <th style="width: 170%"><b>Amount</b></th>
        <th style="width: 150%"><b>Due Date</b></th>
        <th style="width: 150%"><b>Status</b></th>
        <th style="width: 500%"><b>Address</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($quotes as $quote)
        <tr>
            <td>{{ $quote->quote_id }}</td>
            <td>{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat())  }}</td>
            <td>{{ $quote->final_amount }}</td>
            <td>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}</td>
            @if($quote->status == \App\Models\Quote::DRAFT)
                <td> Draft</td>
            @elseif($quote->status == \App\Models\Quote::CONVERTED)
                <td> Converted</td>
            @endif
            <td>{{ $quote->client->address ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
