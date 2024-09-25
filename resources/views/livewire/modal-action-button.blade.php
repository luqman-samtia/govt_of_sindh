<div class="d-flex justify-content-center">
    @if(isset($value['show-route']))
        <a href="javascript:void(0)" wire:key="show-{{$value['data-id']}}" data-id="{{$value['data-id']}}" title="Show"
           class="{{$value['data-show-id']}} btn px-2 text-info fs-3 py-2" data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>
    @endif
    <a title="{{__('messages.common.edit')}}" data-id="{{ $value['data-id'] }}" wire:key="edit-{{$value['data-id']}}"
       class="{{$value['data-edit-id']}} btn px-2 text-primary fs-3 py-2" data-bs-toggle="tooltip">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $value['data-id'] }}"
       wire:key="delete-{{$value['data-id']}}"
       class="{{$value['data-delete-id']}} btn px-2 text-danger fs-3 py-2 delete-btn" data-bs-toggle="tooltip">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
