@if($row->status_label === 'Draft')
    <span class="badge bg-light-warning fs-7">{{$row->status_label}}</span>
@else
    <span class="badge bg-light-success fs-7">{{$row->status_label}}</span>
@endif
