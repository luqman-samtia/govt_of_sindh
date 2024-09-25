<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xxl-5 col-12">
                <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                    <div class="image image-circle image-small">
                        <img src="{{ $client->user->profile_image }}" alt="user" class="object-contain">
                    </div>
                    <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                        <h2>{{ $client->user->full_name }}</h2>
                        <a href="mailto:{{ $client->user->email }}" class="text-gray-600 text-decoration-none fs-4">
                            {{ $client->user->email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-7 overflow-hidden">
    <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myTab" role="tablist">
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link tab active p-0" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                type="button" role="tab" aria-controls="overview" aria-selected="true">
                {{ __('messages.invoice.overview') }}
            </button>
        </li>
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices"
                type="button" role="tab" aria-controls="cases" aria-selected="false">
                {{ __('messages.invoices') }}
            </button>
        </li>
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link tab-link p-0" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#quotes"
                type="button" role="tab" aria-controls="quotes" aria-selected="false">
                {{ __('messages.quotes') }}
            </button>
        </li>
        <input type="hidden" id="activeTabOfClient" value="{{ !empty($activeTab) ? $activeTab : 'overview' }}">
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.user.full_name') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->user->full_name) ? $client->user->full_name : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.user.email') }}:</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->user->email) ? $client->user->email : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.user.contact_number') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->user->contact) ? $client->user->contact : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.client.country') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->country->name) ? $client->country->name : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.client.state') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->state->name) ? $client->state->name : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.client.city') }}:</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->city->name) ? $client->city->name : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.client.address') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->address) ? $client->address : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.client.note') }}:</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->note) ? $client->note : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.setting.company_name') }}:</label>
                            <span
                                class="fs-4 text-gray-800">{{ $client->company_name ?? __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ $vatNoLabel }}:</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->vat_no) ? $client->vat_no : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-sm-0 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.common.created_at') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->user->created_at) ? $client->user->created_at->diffForHumans() : __('messages.common.n/a') }}</span>
                        </div>
                        @php
                            $clientUpdateTime = $client->updated_at;
                            $userUpdateTime = $client->user->updated_at;
                            $updateTime = max($clientUpdateTime, $userUpdateTime);
                        @endphp
                        <div class="col-sm-6 d-flex flex-column">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.common.updated_at') }}
                                :</label>
                            <span
                                class="fs-4 text-gray-800">{{ !empty($client->user->updated_at) ? $updateTime->diffForHumans() : __('messages.common.n/a') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
            @include('clients.invoice.index')
        </div>
        <div class="tab-pane fade" id="quotes" role="tabpanel" aria-labelledby="quotes-tab">
            @include('clients.quote.index')
        </div>
    </div>
</div>
