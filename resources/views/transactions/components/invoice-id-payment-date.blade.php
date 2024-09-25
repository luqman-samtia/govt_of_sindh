
@if(isset($value['invoice-id-route']))
<a href="{{$value['invoice-id-route']}}"><span class="badge bg-light-info fs-7 mt-1">{{$value['invoice-id']}}</span></a>
@endif
@php
    $currentRouteName = Route::currentRouteName();
@endphp
@if(isset($value['payment-date']))
<div class="badge bg-primary">
    <div>{{ \Carbon\Carbon::parse($value['payment-date'])->format(currentDateFormat()) }} {{ (($currentRouteName == 'transactions.index') || ($currentRouteName == 'client.transactions.index')) ? \Carbon\Carbon::parse($value['payment-date'])->isoFormat('hh:mm A') : '' }}</div>
</div>
@endif

