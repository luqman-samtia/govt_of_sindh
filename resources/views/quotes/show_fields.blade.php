@php
    $auth =  Auth::check();
@endphp
<div>
    @if($auth && $isPublicView)
        <div class="d-flex overflow-auto h-55px">
            <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap">
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link active p-0" id="overview-tab" data-bs-toggle="tab"
                            data-bs-target="#overview"
                            type="button" role="tab" aria-controls="overview" aria-selected="true">
                        {{ __('messages.quote.overview') }}
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="note_terms-tab" data-bs-toggle="tab" data-bs-target="#note_terms"
                            type="button" role="tab" aria-controls="note_terms" aria-selected="false">
                        {{ __('messages.quote.note_terms') }}
                    </button>
                </li>
            </ul>
        </div>
    @endif
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="card">
                <div class="d-flex flex-column">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-9">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="d-flex mb-md-10 mb-5">
                                            <div class="image image-circle image-small ">
                                                <img src="{{ getLogoUrl() }}" alt="user" class="object-contain">
                                            </div>
                                            <h3 class="ps-7">{{ __('messages.quote.quote') }}
                                                #{{ $quote->quote_id }}
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-6">
                                        <div class="d-flex flex-column mb-md-10 mb-5 mt-3 mt-md-0">
                                            <label for="name"
                                                   class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.quote_date').':' }}</label>
                                            <span class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-6">
                                        <div class="d-flex flex-column mb-md-10 mb-5 mt-3 mt-md-0">
                                            <label for="name"
                                                   class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.due_date').':' }}</label>
                                            <span class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-3 mb-sm-0">
                                        @if($isPublicView)
                                            <a target="_blank"
                                               href="{{ route('quotes.pdf',['quote' => $quote->id]) }}"
                                               class="btn btn-sm btn-success text-white">{{ __('messages.quote.print_quote') }}</a>
                                        @else
                                            <a target="_blank"
                                               href="{{ route('public-view-quote.pdf',['quote' => $quote->quote_id]) }}"
                                               class="btn btn-sm btn-success text-white">{{ __('messages.quote.print_quote') }}</a>
                                        @endif
                                    </div>
                                    <div class="col-lg-5 col-md-6">
                                        <div class="d-flex flex-column mb-md-10 mb-5">
                                            <label for="name"
                                                   class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.issue_for').':' }}</label>
                                            <span class="fs-4 text-gray-800 mb-3">{{ $quote->client->user->full_name }}</span>
                                            <p class="text-gray-700 fs-4 mb-0">
                                                @if(isset($quote->client->address) && !empty($quote->client->address))
                                                    {{ ucfirst($quote->client->address) }}
                                                @else
                                                    {{ "N/A" }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-md-6">
                                        <div class="d-flex flex-column mb-md-10 mb-5">
                                            <label for="name"
                                                   class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.issue_by').':' }}</label>
                                            <span class="fs-4 text-gray-800 mb-3">{{ getAppName() }}</span>
                                            <p class="text-gray-700 fs-4 mb-0">
                                                {{ getSettingValue('company_address') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped box-shadow-none mt-4">
                                            <thead>
                                            <tr>
                                                <th scope="col">{{ __('messages.product.product') }}</th>
                                                <th scope="col">{{ __('messages.quote.qty') }}</th>
                                                <th scope="col" class="text-end">{{ __('messages.quote.price') }}</th>
                                                <th scope="col" class="text-end">{{ __('messages.quote.amount') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($quote->quoteItems as $index => $quoteItem)
                                                <tr>
                                                    <td class="py-4">{{ isset($quoteItem->product->name)?$quoteItem->product->name:$quoteItem->product_name?? __('messages.common.n/a') }}</td>
                                                    <td class="py-4">{{ $quoteItem->quantity }}</td>
                                                    <td class="py-4 text-end min-width-130px">{{  isset($quoteItem->price) ? getCurrencyAmount($quoteItem->price,true,$quote->tenant_id) : __('messages.common.n/a') }}</td>
                                                    <td class="py-4 text-end min-width-130px">{{ isset($quoteItem->total) ? getCurrencyAmount($quoteItem->total,true,$quote->tenant_id) : __('messages.common.n/a') }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-5 ms-lg-auto mt-4">
                                        <div class="border-top">
                                            <table class="table table-borderless box-shadow-none mb-0 mt-5">
                                                <tbody>
                                                <tr>
                                                    <td class="ps-0">{{ __('messages.quote.sub_total').(':') }}</td>
                                                    <td class="text-gray-900 text-end pe-0">{{ isset($quote->amount) ? getCurrencyAmount($quote->amount,true,$quote->tenant_id) : __('messages.common.n/a') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-0">{{ __('messages.quote.discount').(':') }}</td>
                                                    <td class="text-gray-900 text-end pe-0">
                                                        @if($quote->discount == 0 || !isset($quote))
                                                            <span>N/A</span>
                                                        @else
                                                            @if( $quote->discount_type == \App\Models\Quote::FIXED)
                                                                {{ getCurrencyAmount($quote->discount,true,$quote->tenant_id) }}
                                                            @else
                                                                {{ getCurrencyAmount($quote->amount * $quote->discount / 100,true,$quote->tenant_id)}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-0">{{ __('messages.quote.total').(':') }}</td>
                                                    <td class="text-gray-900 text-end pe-0">
                                                        {{ getCurrencyAmount($quote->final_amount,true,$quote->tenant_id) }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 mb-5 mb-lg-0">
                                <div class="bg-gray-100 rounded-15 p-md-7 p-5 h-100 mt-xxl-0 mt-5 col-xxl-9 ms-xxl-auto w-100">
                                    <div class="mb-8">
                                        @if($quote->status == \App\Models\Quote::DRAFT)
                                            <span class="badge bg-light-warning me-5">Draft</span>
                                        @elseif($quote->status == \App\Models\Quote::CONVERTED)
                                            <span class="badge bg-light-success me-5">Converted</span>
                                        @endif
                                    </div>

                                    <h3 class="mb-5">{{ __('messages.quote.client_overview') }}</h3>
                                    <div class="row">
                                        <div class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7 mb-lg-0 mb-4">
                                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.client_name') }}</label>
                                            @if($auth && \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                                <a href="{{ route('clients.show', ['clientId' => $quote->client->id]) }}"
                                                   class="link-primary text-decoration-none">{{ $quote->client->user->full_name }}</a>

                                            @else
                                                <a href="#" class="link-primary fs-4 text-decoration-none">{{ $quote->client->user->full_name }}</a>
                                            @endif
                                        </div>
                                        <div class="col-xxl-12 col-lg-4 col-sm-6 d-flex flex-column mb-xxl-7 mb-lg-0 mb-4">
                                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.quote.client_email') }}</label>
                                            <span class="fs-4 text-gray-900">{{ $quote->client->user->email }}</span>
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
                            <div class="fw-bold text-gray-600 fs-7">{{ __('messages.quote.note').':' }}</div>
                            <div class="fs-6">{!! $quote->note ?? __('messages.quote.note_not_found') !!}</div>
                        </div>
                        <div class="col-lg-12 mb-5">
                            <div class="fw-bold text-gray-600 fs-7">{{ __('messages.quote.terms').':' }}</div>
                            <div
                                class="fs-6">{!! $quote->term ?? __('messages.quote.terms_not_found') !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
