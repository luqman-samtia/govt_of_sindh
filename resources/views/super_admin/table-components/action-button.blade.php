<div class="width-80px text-center">
    @if(isset($value['show-route']))
        <a href="{{ $value['show-route'] }}" title="{{__('messages.show') }}"
           class="btn px-2 text-info fs-3 py-2" data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>
    @endif
    @if($value['data-id'] !== \App\Models\User::ADMIN)
        @if(isset($value['edit-route']))
            <a href="{{ $value['edit-route'] }}" class="btn px-2 text-primary fs-3 py-2"
               title="{{__('messages.common.edit') }}"
               data-bs-toggle="tooltip">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endif
        <a href="javascript:void(0)" data-id="{{ $value['data-id'] }}" title="{{ __('messages.common.delete') }}"
           class="{{$value['data-delete-id']}} btn px-2 text-danger fs-3 py-2"
           data-bs-toggle="tooltip" data-turbolinks="false">
            <i class="fa-solid fa-trash"></i>
        </a>
    @endif
</div>

