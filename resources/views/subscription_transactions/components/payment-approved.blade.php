@php
         $approved = __('messages.subscription_plans.approved');
         $denied =  __('messages.subscription_plans.denied');
         $waitingForApproval = __('messages.subscription_plans.waiting_for_approval');
         $selectManualPayment = __('messages.subscription_plans.select_manual_payment');
@endphp
@if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\Role::ROLE_SUPER_ADMIN))
    @if ($row->payment_mode == \App\Models\Transaction::TYPE_CASH && $row->is_manual_payment == 0 && $row->status == 0)
        <div class="d-flex align-items-center">
            <select class="form-select io-select2 approve-status payment-approve"
                    data-id="{{$row->id}}" data-control="select2">
                <option selected="selected" value="">{{$selectManualPayment}}</option>
                <option value="1">{{$approved}}</option>
                <option value="2">{{$denied}}</option>
            </select>
        </div>
    @elseif ($row->is_manual_payment == 1)
        <span class="badge bg-light-success">{{$approved}}</span>
    @elseif ($row->is_manual_payment == 2) {
        <span class="badge bg-light-danger">{{$denied}}</span>
    @else
        N/A
    @endif
 @else
     @if ($row->is_manual_payment == 0 && $row->status == 0)
         <span class="badge badge-light-primary">{{$waitingForApproval}}</span>
     @elseif ($row->is_manual_payment == 1) {
        <span class="badge badge-light-success">{{$approved}}</span>
     @elseif ($row->is_manual_payment == 2) {
        <span class="badge badge-light-danger">{{$denied}}</span>
     @else
         N/A
    @endif
@endif
