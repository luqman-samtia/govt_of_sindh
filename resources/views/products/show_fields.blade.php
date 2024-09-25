<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xxl-5 col-12">
                <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                    <div class="image image-circle image-small">
                        <img src="{{$product->product_image}}" alt="user" class="img-logo">
                    </div>
                    <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                        <h2>{{ $product->name }}</h2>
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
                {{__('messages.invoice.overview')}}
            </button>
        </li>
    </ul>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.product.unit_price') }}:</label>
                            <span class="fs-4 text-gray-800">{{ !empty($product->unit_price) ? getCurrencyAmount($product->unit_price,true): __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.category.category') }}:</label>
                            <span class="fs-4 text-gray-800">{{ !empty($product->category->name) ? $product->category->name: __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.product.code') }}:</label>
                            <span class="fs-4 text-gray-800">{{ !empty($product->code) ? $product->code: __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.product.description') }}:</label>
                            <span class="fs-4 text-gray-800">{{ !empty($product->description) ? $product->description: __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-4 text-gray-600">{{ __('messages.common.last_update') }}:</label>
                            <span class="fs-4 text-gray-800">{{ !empty($product->updated_at) ? $product->updated_at->diffForHumans(): __('messages.common.n/a') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
