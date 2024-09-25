<div class="row">
    {{-- Subscription Plan section starts --}}
    <div class="col-md-4 mb-5">
        <div class="form-group">
            {{ Form::label('name', __('messages.subscription_plans.name').(':'), ['class' => 'form-label required mb-3']) }}
            {{ Form::text('name', null , ['class' => 'form-control form-control-solid','required','placeholder' => __('Entry Plan Name'),'id'=>'name']) }}
        </div>
    </div>
    <div class="col-md-4 mb-5 hide_for_trail">
        <div class="form-group">
            {{ Form::label('currency', __('messages.subscription_plans.currency').(':'), ['class' => 'form-label mb-3 for_trail_label required']) }}
            <select id="planCurrencyType" data-show-content="true" class="form-select io-select2 form-select-solid"
                    name="currency_id" data-control="select2">
                @foreach($currencies as $key => $currency)
                    <option value="{{ $currency['id'] }}" {{ $subscriptionPlan->currency_id == $currency['id'] ? 'selected' : ''}}>
                        {{$currency['icon']}}&nbsp;- {{$currency['code']}}&nbsp; {{$currency['name']}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-5 hide_for_trail">
        <div class="form-group">
            {{ Form::label('price', __('messages.subscription_plans.price').(':'), ['class' => 'form-label mb-3 for_trail_label required']) }}
            {{ Form::number('price', null , ['class' => 'form-control form-control-solid price-input price for_trail_required','placeholder' => __('messages.subscription_plans.price'), 'id'=>'price','required','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0']) }}
        </div>
    </div>
    <div class="col-md-4 mb-5 hide_for_trail">
        <div class="form-group">
            {{ Form::label('frequency', __('messages.subscription_plans.plan_type').(':'), ['class' => 'form-label mb-3 for_trail_label required']) }}
            {{ Form::select('frequency', $planType, null, ['required', 'id' => 'planType','class' => 'form-select io-seletct2  form-select-solid for_trail_required', 'required','data-control' => "select2"]) }}
        </div>
    </div>
    <div class="col-md-4 mb-5 hide_for_trail">
        <div class="form-group">
            {{ Form::label('trial_days', __('messages.subscription_plans.trail_plan').(':'), ['class' => 'form-label mb-3 for_trail_label']) }}
            {{ Form::number('trial_days', null , ['class' => 'form-control form-control-solid price-input price for_trail_required','placeholder' =>  __('messages.subscription_plans.trail_plan'), 'id'=>'trialDays','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0']) }}
        </div>
    </div>
    <div class="col-md-4 mb-5">
        <div class="form-group">
            {{ Form::label('client_limit', __('messages.subscription_plans.client_limit').(':'), ['class' => 'form-label mb-3 required']) }}
            {{ Form::number('client_limit', null , ['class' => 'form-control form-control-solid price-input price','placeholder' => __('messages.subscription_plans.client_limit'), 'id'=>'clientLimit','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0']) }}
        </div>
    </div>
    <div class="col-md-4 mb-5">
        <div class="form-group">
            {{ Form::label('invoice_limit', __('messages.subscription_plans.invoice_limit').(':'), ['class' => 'form-label mb-3 required']) }}
            {{ Form::number('invoice_limit', null , ['class' => 'form-control form-control-solid price-input price','placeholder' => __('messages.subscription_plans.invoice_limit'), 'id'=>'invoiceLimit','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0']) }}
        </div>
    </div>
</div>
<div class="float-end d-flex mt-5">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'btnSave']) }}
    <a href="{{ route('subscription-plans.index') }}"
       class="btn btn-secondary btn-active-light-primary me-2">{{ __('messages.common.cancel') }}</a>
</div>
