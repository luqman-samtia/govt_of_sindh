@php
    $dueAmount = 0;
    $paid = 0;
    if ($row->status != \App\Models\Invoice::PAID){
     foreach ($row->payments as $payment){
         if($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED){
            continue;
        }
        $paid += $payment->amount;
    } 
    }else{
        $paid += $row->final_amount;
    }
    $dueAmount = $row->final_amount - $paid;
@endphp
    
@if($row->status_label == 'Draft') 
    <span class="text-center">{{ __('messages.common.n/a') }}</span>
@else
    @if ($row->final_amount == $paid)
        <span class="badge bg-light-success fs-7">Paid:{{getInvoiceCurrencyAmount($paid,$row->currency_id,true)}}</span><br>
    @elseif($row->status == 3)
        <span class="badge bg-light-success fs-7">Paid:{{getInvoiceCurrencyAmount($paid,$row->currency_id,true)}}</span><br>
        <span class="badge bg-light-danger fs-7 mt-1">Due:{{getInvoiceCurrencyAmount($dueAmount,$row->currency_id,true)}}</span>
    @else
        <span class="badge bg-light-danger fs-7">Due:{{getInvoiceCurrencyAmount($dueAmount,$row->currency_id,true)}}</span>
    @endif
@endif
