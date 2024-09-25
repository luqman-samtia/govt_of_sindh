@if($row->media->count() > 0)
    <a href="{{ route('download.attachments', $row->id) }}" title="{{ __('messages.invoice.download') }}"><i
                class="fa fa-download"></i></a>
@else
    <span>{{ __('messages.common.n/a') }}</span>
@endif
