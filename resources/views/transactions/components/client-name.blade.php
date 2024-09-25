<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('clients.show',$row->invoice->client->id)}}" class="mb-1 text-primary text-decoration-none">{{$row->invoice->client->user->full_name}}</a>
            </div>
        </div>
        <span>{{$row->invoice->client->user->email}}</span>
    </div>
</div>
