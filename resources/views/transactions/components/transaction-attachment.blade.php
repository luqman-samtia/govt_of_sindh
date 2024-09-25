@if($row->payment_attachment)
    <a href="{{ route('transaction.attachment', $row->id) }}" target="_blank"><i class="fa-solid fa-circle-arrow-down fs-3 text-success"></i></a>
@else
{{ __('messages.common.n/a') }}
@endif
