<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xxl-5 col-12">
                <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                    <div class="image image-circle image-small">
                        <img src="{{ $user->profile_image }}" alt="user">
                    </div>
                    <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                        <span class="badge bg-light-success mb-2">{{ $user->roles->first()->display_name }}</span>
                        <h2>{!! $user->full_name !!}</h2>
                        <a href="mailto:{{ $user->email }}" class="text-gray-600 text-decoration-none fs-4">
                            {{ $user->email }}
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
            <button class="nav-link active p-0" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                type="button" role="tab" aria-controls="overview" aria-selected="true">
                {{ __('messages.invoice.overview') }}
            </button>
        </li>
    </ul>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <div
                            class="col-sm-6 d-flex flex-column mb-md-10 mb-5 {{ empty($user->contact) ? 'd-none' : '' }}">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.user.contact_number') }}:</label>
                            <span class="fs-4 text-gray-800">{{ $user->contact }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-4 text-gray-600">{{ __('messages.user.registered_date') . ':' }}</label>
                            <span
                                class="fs-4 text-gray-800">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('jS \of F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
