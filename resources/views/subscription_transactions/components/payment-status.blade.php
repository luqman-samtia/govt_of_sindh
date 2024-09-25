@if($row->status == \App\Models\Transaction::PAID || $row->status == 'paid' || $row->status == \App\Models\Transaction::APPROVED)
    <span class="badge bg-light-success">{{\App\Models\Transaction::PAID}}</span>
@elseif($row->status == \App\Models\Transaction::DENIED)
    <span class="badge bg-light-danger">{{\App\Models\Transaction::DENIED}}</span>
@elseif($row->status == \App\Models\Transaction::UNPAID)
    <span class="badge bg-light-danger">{{\App\Models\Transaction::UNPAID}}</span>
@elseif($row->status == 0)
    <span class="badge bg-light-danger">Processing</span>
@endif
