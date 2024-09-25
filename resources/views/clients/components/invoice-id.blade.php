<a href="{{ route('invoices.show',$row->id) }}"
   class="badge bg-light-primary text-decoration-none">{{$row->invoice_id}}</a>
@if($row->recurring_status)
    <span class="text-primary recurring-cycle-icon" data-bs-toggle="tooltip" data-placement="right"
          title="{{ __('messages.invoice.recurring') .' '. __('messages.invoice.invoice') }}">
                        <i class="fas fa-recycle"></i>
                    </span>
@endif
