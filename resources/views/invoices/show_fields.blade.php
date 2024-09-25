@php
    $auth = \Illuminate\Support\Facades\Auth::check();
    $itemTaxesAmount = $invoice->amount + array_sum($totalTax);
    $invoiceTaxesAmount = ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) / 100;
@endphp
<div>
    @if ($auth && $isPublicView)
        <div class="d-flex overflow-auto h-55px">
            <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap">
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link active p-0" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                        type="button" role="tab" aria-controls="overview" aria-selected="true">
                        {{ __('messages.invoice.overview') }}
                    </button>
                </li>
                @if ($invoice->parentInvoice)
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="parentInvoices-tab" data-bs-toggle="tab"
                            data-bs-target="#parentInvoiceDetail" type="button" role="tab"
                            aria-controls="parentInvoiceDetail" aria-selected="false">
                            {{ __('messages.invoice.parent_invoice') }}
                        </button>
                    </li>
                @endif
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="note_terms-tab" data-bs-toggle="tab" data-bs-target="#note_terms"
                        type="button" role="tab" aria-controls="note_terms" aria-selected="false">
                        {{ __('messages.invoice.note_terms') }}
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="paymentHistory-tab" data-bs-toggle="tab"
                        data-bs-target="#paymentHistory" type="button" role="tab" aria-controls="paymentHistory"
                        aria-selected="false">
                        {{ __('messages.invoice.payment_history') }}
                    </button>
                </li>
                @if ($invoice->child_invoices_count > 0)
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="recurringInvoices-tab" data-bs-toggle="tab"
                            data-bs-target="#recurringInvoices" type="button" role="tab"
                            aria-controls="recurringInvoices" aria-selected="false">
                            {{ __('messages.invoice.recurring_invoices') }}
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    @endif
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="">
                <div class="d-flex flex-column">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-9">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="d-flex align-items-center mb-md-10 mb-5">
                                                <div class="image image-circle image-small">
                                                    <img src="{{ getPublicInvoiceAppURL($invoice->tenant_id) }}"
                                                        alt="logo" class="object-contain" style="width: 100px">
                                                </div>
                                                <h3 class="ps-7">{{ __('messages.invoice.invoice') }}
                                                    #{{ $invoice->invoice_id }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-6">
                                            <div class="d-flex flex-column mb-md-10 mb-5 mt-3 mt-md-0">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.invoice_date') . ':' }}</label>
                                                <span
                                                    class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-6">
                                            <div class="d-flex flex-column mb-md-10 mb-5 mt-3 mt-md-0">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.due_date') . ':' }}</label>
                                                <span
                                                    class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 mb-3 mb-sm-0">
                                            @if ($isPublicView)
                                                <a target="_blank"
                                                    href="{{ route('invoices.pdf', ['invoice' => $invoice->id]) }}"
                                                    class="btn btn-sm btn-success text-white">{{ __('messages.invoice.print_invoice') }}</a>
                                            @else
                                                <a target="_blank"
                                                    href="{{ route('public-view-invoice.pdf', ['invoice' => $invoice->invoice_id]) }}"
                                                    class="btn btn-sm btn-success text-white">{{ __('messages.invoice.print_invoice') }}</a>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex flex-column mb-md-10 mb-5">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.issue_for') . ':' }}</label>
                                                <span
                                                    class="fs-4 text-gray-800 mb-3">{{ $invoice->client->user->full_name }}</span>
                                                <p class="text-gray-700 fs-4 mb-0">
                                                    @if (isset($invoice->client->address) && !empty($invoice->client->address))
                                                        {{ ucfirst($invoice->client->address) }}
                                                    @else
                                                        {{ __('messages.common.n/a') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="d-flex flex-column mb-md-10 mb-5">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.issue_by') . ':' }}</label>
                                                <span
                                                    class="fs-4 text-gray-800 mb-3">{{ getSettingValue('app_name', $invoice->tenant_id) }}</span>
                                                <p class="text-gray-700 fs-4 mb-0 w-75">
                                                    {{ getSettingValue('company_address', $invoice->tenant_id) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column mb-md-10 mb-5">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.payment_qr_codes.payment_qr_code') . ':' }}</label>
                                                <p class="text-gray-700 fs-4 mb-0">
                                                    @if (!empty($invoice->paymentQrCode))
                                                        {{ $invoice->paymentQrCode->title }}
                                                    @else
                                                        {{ __('messages.common.n/a') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-12 table-responsive">
                                            <table class="table table-striped box-shadow-none mt-4">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">{{ __('messages.product.product') }}</th>
                                                        <th scope="col">{{ __('messages.invoice.qty') }}</th>
                                                        <th scope="col">{{ __('messages.invoice.price') }}</th>
                                                        <th scope="col">
                                                            {{ __('messages.invoice.tax') . ' (in %)' }}
                                                        </th>
                                                        <th scope="col">{{ __('messages.invoice.amount') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($invoice->invoiceItems as $index => $invoiceItem)
                                                        <tr>
                                                            <td class="py-4">
                                                                {{ isset($invoiceItem->product->name) ? $invoiceItem->product->name : $invoiceItem->product_name ?? __('messages.common.n/a') }}
                                                            </td>
                                                            <td class="py-4">{{ $invoiceItem->quantity }}</td>
                                                            <td class="py-4 min-width-130px">
                                                                {{ !empty($invoiceItem->price) ? getInvoiceCurrencyAmount($invoiceItem->price, $invoice->currency_id, true, $invoice->tenant_id) : __('messages.common.n/a') }}
                                                            </td>
                                                            <td class="py-4">
                                                                @foreach ($invoiceItem->invoiceItemTax as $keys => $tax)
                                                                    {{ $tax->tax != 0 ? $tax->tax : __('messages.common.n/a') }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td class="py-4 min-width-130px">
                                                                {{ !empty($invoiceItem->total) ? getInvoiceCurrencyAmount($invoiceItem->total, $invoice->currency_id, true, $invoice->tenant_id) : __('messages.common.n/a') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if (!empty($invoice->paymentQrCode))
                                            <div class="col-lg-2 mt-5 mx-3 ">
                                                <span class="fs-4 text-gray-600">
                                                    {{ __('messages.payment_qr_codes.payment_qr_code') . ':' . ' ' }}
                                                </span>
                                                <img class="mt-3" src="{{ $invoice->paymentQrCode->qr_image }}"
                                                    width="120" height="120">
                                            </div>
                                        @endif
                                        @if (count($invoice->invoiceTaxes) > 0)
                                            <div class="col-lg-7">
                                                <div class="d-flex align-items-center mt-2">
                                                    <label for="invoice-taxes"
                                                        class="fs-4 text-gray-600 me-2">{{ __('messages.tax_information') . ':' . ' (%)' }}</label>
                                                </div>
                                                <div class="mt-2">
                                                    @foreach ($invoice->invoiceTaxes as $tax)
                                                        <div class="mb-1">
                                                            <b>{{ $tax->value . '%' }}</b>{{ ' (' . $tax->name . ')' }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-lg-5 ms-lg-auto mt-4">
                                            <div class="border-top">
                                                <table class="table table-borderless  box-shadow-none mb-0 mt-5">
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-0">
                                                                {{ __('messages.invoice.sub_total') . ':' }}</td>
                                                            <td class="text-gray-900 text-end pe-0">
                                                                {{ !empty($invoice->amount) ? getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true, $invoice->tenant_id) : __('messages.common.n/a') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-0">
                                                                {{ __('messages.invoice.discount') . ':' }}</td>
                                                            <td class="text-gray-900 text-end pe-0">
                                                                @php $percentageDiscount = ($itemTaxesAmount + $invoiceTaxesAmount); @endphp
                                                                @if ($invoice->discount == 0 || !isset($invoice))
                                                                    <span>N/A</span>
                                                                @else
                                                                    @if ($invoice->discount_type == \App\Models\Invoice::FIXED)
                                                                        {{ getInvoiceCurrencyAmount($invoice->discount, $invoice->currency_id, true, $invoice->tenant_id) }}
                                                                    @else
                                                                        {{ getInvoiceCurrencyAmount(($percentageDiscount * $invoice->discount) / 100, $invoice->currency_id, true, $invoice->tenant_id) }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-0">{{ __('messages.invoice.tax') . ':' }}
                                                            </td>
                                                            @php $totalTaxes = (array_sum($totalTax) + $invoiceTaxesAmount)  @endphp
                                                            <td class="text-gray-900 text-end pe-0">
                                                                {!! numberFormat($totalTaxes) != 0
                                                                    ? getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true, $invoice->tenant_id)
                                                                    : __('messages.common.n/a') !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-0">
                                                                {{ __('messages.invoice.total') . ':' }}</td>
                                                            <td class="text-gray-900 text-end pe-0">
                                                                {{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true, $invoice->tenant_id) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 mb-5 mb-lg-0">
                                    <div
                                        class="bg-gray-100 rounded-15 p-md-7 p-5 h-100 mt-xxl-0 mt-5 col-xxl-9 ms-xxl-auto w-100">
                                        <div class="mb-8">
                                            @if ($invoice->status == \App\Models\Invoice::UNPAID)
                                                <span class="badge bg-light-danger">Pending Payment</span>
                                            @elseif($invoice->status == \App\Models\Invoice::PAID)
                                                <span class="badge bg-light-success me-2">Paid</span>
                                            @elseif($invoice->status == \App\Models\Invoice::PARTIALLY)
                                                <span class="badge bg-light-primary">Partially Paid</span>
                                            @elseif($invoice->status == \App\Models\Invoice::DRAFT)
                                                <span class="badge bg-light-warning me-5">Draft</span>
                                            @elseif($invoice->status == \App\Models\Invoice::OVERDUE)
                                                <span class="badge bg-light-danger">Overdue</span>
                                            @elseif($invoice->status == \App\Models\Invoice::PROCESSING)
                                                <span class="badge bg-light-primary">Processing</span>
                                            @endif
                                            @if ($invoice->status == \App\Models\Invoice::DRAFT)
                                                <button class="btn btn-success send-btn btn-sm"
                                                    data-id="{{ $invoice->id }}">{{ __('messages.invoice.send') }}
                                                </button>
                                            @endif
                                        </div>

                                        <h3 class="mb-5">{{ __('messages.invoice.client_overview') }}</h3>
                                        <div class="row">
                                            <div
                                                class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7 mb-lg-0 mb-4">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.client_name') }}</label>
                                                @if ($auth && \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                                    <a href="{{ route('clients.show', ['clientId' => $invoice->client->id]) }}"
                                                        class="link-primary text-decoration-none">{{ $invoice->client->user->full_name }}</a>
                                                @else
                                                    <a href="#"
                                                        class="link-primary fs-4 text-decoration-none">{{ $invoice->client->user->full_name }}</a>
                                                @endif
                                            </div>
                                            <div
                                                class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7 mb-lg-0 mb-4">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.client_email') }}</label>
                                                <span
                                                    class="fs-4 text-gray-900">{{ $invoice->client->user->email ?? __('messages.common.n/a') }}</span>
                                            </div>
                                            <div class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.paid_amount') }}</label>
                                                <span
                                                    class="fs-4 text-gray-900">{{ getInvoiceCurrencyAmount($paid, $invoice->currency_id, true, $invoice->tenant_id) }}</span>
                                            </div>
                                            <div class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7">
                                                <label for="name"
                                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.remaining_amount') }}</label>
                                                <span
                                                    class="fs-4 text-gray-900">{{ getInvoiceCurrencyAmount($dueAmount, $invoice->currency_id, true, $invoice->tenant_id) }}
                                                </span>
                                            </div>
                                            @if (!$auth && $paid !== $invoice->final_amount)
                                                <a href="javascript:void(0)"
                                                    class="btn btn-sm btn-warning mt-5 open-public-payment-modal">{{ __('messages.invoice.make_payment') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="note_terms" role="tabpanel">
            <div class="card">
                <div class="card-body pt-5">
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            <div class="fw-bold text-gray-600 fs-7">{{ __('messages.invoice.note') . ':' }}</div>
                            <div class="fs-6">{!! $invoice->note ?? __('messages.invoice.note_not_found') !!}</div>
                        </div>
                        <div class="col-lg-12 mb-5">
                            <div class="fw-bold text-gray-600 fs-7">{{ __('messages.invoice.terms') . ':' }}</div>
                            <div class="fs-6">{!! $invoice->term ?? __('messages.invoice.terms_not_found') !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="paymentHistory" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 ">
                    <livewire:payment-history-table invoiceId="{{ $invoice->id }}" />
                </div>
            </div>
        </div>
        @if ($invoice->parentInvoice)
            <div class="tab-pane fade show" id="parentInvoiceDetail" role="tabpanel" aria-labelledby="overview-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.invoice') }}
                                    :</label>
                                <a href="{{ route('invoices.show', $invoice->parentInvoice->id) }}" target="_blank"
                                    class="fs-4 text-decoration-none text-primary">#{{ $invoice->parentInvoice->invoice_id }}</a>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.common.status') }}
                                    :</label>
                                <span class="fs-4 text-gray-800">{{ $invoice->parentInvoice->status_label }}</span>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.invoice_date') }}
                                    :</label>
                                <span
                                    class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($invoice->parentInvoice->invoice_date)->translatedFormat(currentDateFormat()) }}</span>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.due_date') }}
                                    :</label>
                                <span
                                    class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($invoice->parentInvoice->due_date)->translatedFormat(currentDateFormat()) }}</span>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.paid_amount') }}
                                    :</label>
                                <span
                                    class="fs-4 text-gray-800">{{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->parentInvoice->id), $invoice->parentInvoice->currency_id, true) }}</span>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-4 text-gray-600">{{ __('messages.invoice.remaining_amount') }}
                                    :</label>
                                <span
                                    class="fs-4 text-gray-800">{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->parentInvoice->id), $invoice->parentInvoice->currency_id, true) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="tab-pane fade show" id="recurringInvoices" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 ">
                    <livewire:recurring-invoices-table invoiceId="{{ $invoice->id }}" />
                </div>
            </div>
        </div>
    </div>
</div>
