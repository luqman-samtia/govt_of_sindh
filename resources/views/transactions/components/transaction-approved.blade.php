@php
    $approved = __('messages.subscription_plans.approved');
    $denied =  __('messages.subscription_plans.denied');
    $selectManualPayment = __('messages.subscription_plans.select_manual_payment');
@endphp

@if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\Role::ROLE_ADMIN))
    @if ($row->is_approved == \App\Models\Payment::PENDING && $row->payment_mode == \App\Models\Payment::MANUAL)
        <div class="d-flex align-items-center">
            <select class="form-select io-select2 approve-status transaction-approve"
                    data-id="{{$row->id}}" data-control="select2">
                <option selected="selected" value="">{{$selectManualPayment}}</option>
                <option value="{{\App\Models\Payment::APPROVED}}">{{$approved}}</option>
                <option value="{{\App\Models\Payment::REJECTED}}">{{$denied}}</option>
            </select>
        </div>
    @elseif ($row->is_approved == \App\Models\Payment::APPROVED )
        <span class="badge bg-light-success">{{$approved}}</span>
    @elseif ($row->is_approved == \App\Models\Payment::REJECTED )
    <span class="badge bg-light-danger">{{$denied}}</span>
    @else
        N/A
    @endif
@endif
