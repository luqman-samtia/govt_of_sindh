<div class="dropup position-static" wire:key="{{ $row->id }}">
    <button wire:key="quote-{{ $row->id }}" type="button" title="{{ __('messages.common.action') }}"
            class="dropdown-toggle hide-arrow btn px-2 text-primary fs-3 pe-0"
            id="dropdownMenuButton1" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
    <ul class="dropdown-menu min-w-170px" aria-labelledby="dropdownMenuButton1">
        @php
            $isEdit = ($row->status == 0) ? 0 : 1;
        @endphp
        @if($isEdit != 1)
        <li>
            <a href="{{route('client.quotes.edit',$row->id)}}" class="dropdown-item text-hover-primary me-1 edit-btn"
               data-bs-toggle="tooltip" title="Edit" data-turbo="false">
                <?php echo __('messages.common.edit') ?>
            </a>
        </li>
        @endif
        <li>
            <a href="#" data-id="{{$row->id}}" class="delete-btn dropdown-item me-1 text-hover-primary client-quote-delete-btn"
               data-bs-toggle="tooltip" title="Delete">
                <?php echo __('messages.common.delete') ?>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('client.quotes.pdf', $row->id)}}"
               target="_blank"><?php echo __('messages.invoice.download') ?></a>
        </li>
    </ul>
</div>

