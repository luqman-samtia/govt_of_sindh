@if($row->status != 1)
    <a href="javascript:void(0)" data-id="{{$row->id}}" class="btn btn-secondary">{{__('messages.impersonate')}}</a>
@else
    <a href="javascript:void(0)" data-id="{{$row->id}}"
       class="btn btn-primary user-impersonate">{{__('messages.impersonate')}}</a>
@endif


