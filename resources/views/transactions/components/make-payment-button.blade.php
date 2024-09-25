@php
    $invoice = \App\Models\Invoice::with('client.user')->find($this->invoiceId);
    $user = \App\Models\User::find($invoice->client->user->id);
@endphp
@role('client')
    @if ($invoice->status_label != 'Paid')
        <a href="{{ route('clients.payments.show', $this->invoiceId) }}"
            class="btn btn-primary">{{ __('messages.quote.make_payment') }}
        </a>
    @endif
@endrole
