<div class="d-flex align-items-center">
    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        <a href="{{route('clients.show', $row->client->id)}}">
            <div class="image image-circle image-mini me-3">
                <img src="{{$row->profile_image}}" alt="" class="user-img object-cover" width="50px"
                     height="50px">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{route('clients.show', $row->client->id)}}"
           class="mb-1 text-decoration-none">{{$row->full_name}}</a>
        <span>{{$row->email}}</span>
    </div>
</div>
