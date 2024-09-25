@php $invoiceCount = \App\Models\Invoice::whereClientId($value['clientId'])
        ->whereTenantId(getLogInUser()->tenant_id)->withoutGlobalScope(new \Stancl\Tenancy\Database\TenantScope())->count();
@endphp
<span class="badge badge-circle bg-success me-2">
    <a href="{{ route('clients.show',$value['clientId'].'?active=invoices') }}"
       class="text-decoration-none text-white">{{ $invoiceCount }}</a>
</span>
