<div class="my-3 my-sm-3" wire:ignore>
    <div class="date-picker-space me-2">
        <input class="form-control text-center removeFocus" placeholder="Pick date range" id="paymentDateFilter" readonly>
    </div>
</div>
<div class="dropdown my-3 my-sm-3 me-2">
    <button class="btn btn-success text-white dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ __('messages.common.export') }}
    </button>
    <ul class="dropdown-menu export-dropdown">
        <a href="javascript:void(0)" class="dropdown-item" data-turbo="false" id="adminPaymentExcelExport">
            <i class="fas fa-file-excel me-1"></i> {{ __('messages.invoice.excel_export') }}
        </a>
        <a href="javascript:void(0)" class="dropdown-item" data-turbo="false" id="adminPaymentPdfExport">
            <i class="fas fa-file-pdf me-1"></i> {{ __('messages.pdf_export') }}
        </a>
    </ul>
</div>
<div class="my-3 my-sm-3">
    <a class="btn btn-primary addPayment">
        {{ __('messages.payment.add_payment') }}
    </a>
</div>
