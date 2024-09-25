<div class="dropup position-static" wire:key="{{ $row->id }}">

    <a class="px-2" href="{{ route('invoices.pdf', $row->id) }}" title="{{ __('messages.invoice.download') }}"
        target="_blank"><i class="fa-solid fa-download fs-2"></i></a>

    <button wire:key="invoice-{{ $row->id }}" type="button" title="{{ __('messages.common.action') }}"
        class="dropdown-toggle hide-arrow btn px-2 text-primary fs-2 pe-0" id="dropdownMenuButton1"
        data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
    @php
        $isEdit = $row->status == 2 || $row->status == 3 ? 1 : 0;
        $isPaid = $row->status == 2 || $row->status == 0 ? 1 : 0;
        $isDraft = $row->status == 0 ? 0 : 1;
    @endphp
    <ul class="dropdown-menu min-w-170px" aria-labelledby="dropdownMenuButton1">
        @if ($isEdit != 1)
            <li>
                <a href="{{ route('invoices.edit', $row->id) }}" class="dropdown-item text-hover-primary me-1 edit-btn"
                    data-bs-toggle="tooltip" title="{{ __('messages.common.edit') }}" data-turbo="false">
                    <?php echo __('messages.common.edit'); ?>
                </a>
            </li>
        @endif
        <li>
            <a href="#" data-id="{{ $row->id }}"
                class="invoice-delete-btn dropdown-item me-1 text-hover-primary" data-bs-toggle="tooltip"
                title="{{ __('messages.common.delete') }}">
                <?php echo __('messages.common.delete'); ?>
            </a>
        </li>
        {{-- <li>
            <a class="dropdown-item" href="{{ route('invoices.pdf', $row->id) }}" data-bs-toggle="tooltip"
                title="{{ __('messages.invoice.download') }}" target="_blank"><?php echo __('messages.invoice.download'); ?>
            </a>
        </li> --}}
        @if ($isPaid != 1)
            <li>
                <a href="#" data-id="{{ $row->id }}"
                    class="reminder-btn dropdown-item me-1 text-hover-primary" data-bs-toggle="tooltip"
                    title="{{ __('messages.common.reminder') }}">
                    <?php echo __('messages.common.reminder'); ?>
                </a>
            </li>
        @endif
        @if ($isDraft)
            <li>
                <a href="javascript:void(0)" data-url="{{ route('invoice-show-url', $row->invoice_id) }}"
                    class="dropdown-item text-hover-primary me-1 edit-btn  invoice-url" data-bs-toggle="tooltip"
                    title="{{ __('messages.invoice.invoice_url') }}" onclick="copyToClipboard($(this).data('url'))">
                    {{ __('messages.invoice.invoice_url') }}
                </a>
            </li>
        @endif
        <li>
            <a href="javascript:void(0)" data-id="{{ $row->id }}"
                class="dropdown-item text-hover-primary me-1 update-recurring" data-bs-toggle="tooltip"
                title="{{ $row->recurring_status ? __('messages.invoice.stop_recurring') : __('messages.invoice.start_recurring') }}">
                {{ $row->recurring_status ? __('messages.invoice.stop_recurring') : __('messages.invoice.start_recurring') }}
            </a>
        </li>
        @if ($isPaid != 1)
            <li>
                <a href="javascript:void(0)" data-id="{{ $row->id }}"
                    class="dropdown-item text-hover-primary me-1 send-whatsapp-invoice-modal"
                    data-bs-toggle="tooltip" title="{{ __('messages.invoice.send_whatsapp') }}">
                    {{ __('messages.invoice.send_whatsapp') }}
                </a>
            </li>
        @endif
    </ul>
</div>
