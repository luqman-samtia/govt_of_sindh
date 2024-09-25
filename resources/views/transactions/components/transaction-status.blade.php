@if($row->is_approved == \App\Models\Payment::APPROVED && $row->payment_mode == 1)
    <span class="badge bg-light-success">{{\App\Models\Payment::PAID}}</span>
@elseif($row->is_approved == \App\Models\Payment::PENDING && $row->payment_mode == 1)
    <span class="badge bg-light-danger">{{\App\Models\Payment::PROCESSING}}</span>
@elseif($row->is_approved == \App\Models\Payment::REJECTED && $row->payment_mode == 1)
    <span class="badge bg-light-danger">{{\App\Models\Payment::DENIED}}</span>
@else
    <span class="badge bg-light-success">{{\App\Models\Payment::PAID}}</span>
@endif
