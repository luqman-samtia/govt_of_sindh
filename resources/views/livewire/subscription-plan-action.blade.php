<div class="width-120px text-center">
    <a href="{{route('subscription-plans.show',$row->id)}}" title="{{ __('messages.show') }}"
       class="btn px-2 text-info fs-3 py-2" data-bs-toggle="tooltip">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{route('subscription-plans.edit', $row->id)}}" title="<?php echo __('messages.common.edit') ?>"
       data-bs-toggle="tooltip" class="btn px-2 text-primary fs-3 py-2 edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @if(!$row->is_default)
        <a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}"
           data-bs-toggle="tooltip" data-turbolinks="false" class="plan-delete-btn btn px-2 text-danger fs-3 py-2">
            <i class="fa-solid fa-trash"></i>
        </a>
    @endif
</div>



