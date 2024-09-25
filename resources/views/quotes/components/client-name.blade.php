<div class="d-flex align-items-center">
    <div class="symbol symbol-circle symbol-50px overflow-hidden me-2">
        <a href="{{route('clients.show', $row->client->id)}}">
            <div class="image image-circle image-mini me-2">
                <img src="{{$row->client->user->profile_image}}" alt="" class="user-img" width="50px" height="50px">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('clients.show', $row->client->id)}}"
                   class="mb-1 text-primary text-decoration-none">{{$row->client->user->full_name}}</a>&nbsp;
                <a href="{{route('quotes.show', $row->id)}}"
                   class="badge bg-light-info text-decoration-none">{{$row->quote_id}}</a>
            </div>
        </div>
        <span>{{$row->client->user->email}}</span>
    </div>
</div>


