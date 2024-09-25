<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.enquiry.name').(':')}}</label>
                <span class="fs-5 text-gray-800">{{ $enquiry->full_name }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.enquiry.email').(':')}}</label>
                <span class="fs-5 text-gray-800">{{ $enquiry->email }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-0 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.common.status').(':')}}</label>
                <span class="badge w-2 fs-6 bg-light-{{!empty($enquiry->status) ? 'success' : 'danger'}}">{{($enquiry->status) ? __('messages.enquiry.read') : __('messages.enquiry.unread')}}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.created_on').(':') }}</label>
                <span class="fs-5 text-gray-800" title="{{ date('jS M, Y', strtotime($enquiry->created_at)) }}">{{ $enquiry->created_at->diffForHumans() }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at').(':') }}</label>
                <span class="fs-5 text-gray-800" title="{{ date('jS M, Y', strtotime($enquiry->updated_at)) }}">{{ $enquiry->updated_at->diffForHumans() }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.enquiry.message').(':') }}</label>
                <span class="fs-5 text-gray-800">{!! nl2br(e($enquiry->message)) !!}</span>
            </div>
        </div>
    </div>
</div>

