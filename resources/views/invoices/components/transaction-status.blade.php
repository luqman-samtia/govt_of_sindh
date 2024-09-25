@if($row->status_label === 'Paid')
<span class="badge bg-light-success fs-7">{{$row->status_label}}</span>
@elseif($row->status_label === 'Unpaid')
<span class="badge bg-light-danger fs-7">{{$row->status_label}}</span>
@elseif($row->status_label === 'Partially Paid')
<span class="badge bg-light-primary fs-7">{{$row->status_label}}</span>
@elseif($row->status_label === 'Draft')
<span class="badge bg-light-warning fs-7">{{$row->status_label}}</span>
@else
<span class="badge bg-light-danger fs-7">{{$row->status_label}}</span>
@endif
