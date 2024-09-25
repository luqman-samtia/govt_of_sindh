<div class="badge bg-primary">
    <div class="mb-2">{{\Carbon\Carbon::parse($row->created_at)->translatedFormat('h:i A')}}</div>
    <div>{{\Carbon\Carbon::parse($row->created_at)->translatedFormat('jS  M, Y')}}</div>
</div>
