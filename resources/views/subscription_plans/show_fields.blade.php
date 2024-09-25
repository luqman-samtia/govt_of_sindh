<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.subscription_plans.name').(':')}}</label>
                <span class="fs-5 text-gray-800">{{ $subscriptionPlan->name }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.subscription_plans.currency').(':')}}</label>
                <span class="fs-5 text-gray-800">{{ strtoupper($subscriptionPlan->currencies->code) }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-0 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.subscription_plans.plan_type').(':')}}</label>
                @if($subscriptionPlan->frequency == \App\Models\SubscriptionPlan::MONTH)
                <span class="fs-5 text-gray-800">{{ \App\Models\SubscriptionPLAN::PLAN_TYPE[$subscriptionPlan->frequency] }}</span>
                @elseif($subscriptionPlan->frequency == \App\Models\SubscriptionPlan::YEAR)
                <span class="fs-5 text-gray-800">{{ \App\Models\SubscriptionPLAN::PLAN_TYPE[$subscriptionPlan->frequency] }}</span>
                @else
                    {{ __('messages.common.n/a') }}
                @endif
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.subscription_plans.valid_until').(':')}}</label>
                <span class="fs-5 text-gray-800"> {{ $subscriptionPlan->trial_days != 0 ? $subscriptionPlan->trial_days : __('messages.common.n/a') }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{__('messages.subscription_plans.active_plan').(':')}}</label>
                <span class="fs-5 text-gray-800">{{ $subscriptionPlan->subscription->count() }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.created_on').(':') }}</label>
                <span class="fs-5 text-gray-800" title="{{ date('jS M, Y', strtotime($subscriptionPlan->created_at)) }}">{{ $subscriptionPlan->created_at->diffForHumans() }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at').(':') }}</label>
                <span class="fs-5 text-gray-800" title="{{ date('jS M, Y', strtotime($subscriptionPlan->updated_at)) }}">{{ $subscriptionPlan->updated_at->diffForHumans() }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.subscription_plans.client_limit').(':') }}</label>
                <span class="fs-5 text-gray-800"> {{ $subscriptionPlan->client_limit }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.subscription_plans.invoice_limit').(':') }}</label>
                <span class="fs-5 text-gray-800"> {{ $subscriptionPlan->invoice_limit }}</span>
            </div>
        </div>
    </div>
</div>
