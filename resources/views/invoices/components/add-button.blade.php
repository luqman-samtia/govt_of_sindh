<div class="my-3 my-sm-3" wire:ignore>
    <div class="date-picker-space me-2">
        <input class="form-control text-center removeFocus" id="dateRange" readonly>
    </div>
</div>
<div class="dropdown d-flex align-items-center me-4 me-md-2" wire:ignore>
    <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
        id="invoiceFilters" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
        <p class="text-center">
            <i class='fas fa-filter'></i>
        </p>
    </button>
    <div class="dropdown-menu py-0" aria-labelledby="invoiceFilters">
        <div class="text-start border-bottom py-4 px-7">
            <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
        </div>
        <div class="p-5">
            <div class="mb-5">
                <label for="filterBtn" class="form-label">{{ __('messages.common.status') }}:</label>
                {{ Form::select('status', getInvoiceStatusArr(), null, [
                    'class' => 'form-control form-control-solid form-select',
                    'data-control' => 'select2',
                    'id' => 'invoiceStatusId',
                ]) }}
            </div>
            <div class="mb-5">
                <label for="filterBtn" class="form-label">{{ __('messages.invoice.recurring') }}:</label>
                {{ Form::select('recurring', getRecurringInvoiceStatusArr(), null, [
                    'class' => 'form-control form-control-solid form-select',
                    'data-control' => 'select2',
                    'id' => 'invoiceRecurringId',
                ]) }}
            </div>
            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary"
                    id="invoiceResetFilters">{{ __('messages.common.reset') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="dropdown my-3 my-sm-3 me-2">
    <button class="btn btn-success text-white dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ __('messages.common.export') }}
    </button>
    <ul class="dropdown-menu export-dropdown">
        <a href="{{ route('admin.invoicesExcel') }}" class="dropdown-item" data-turbo="false">
            <i class="fas fa-file-excel me-1"></i> {{ __('messages.invoice.excel_export') }}
        </a>
        <a href="{{ route('admin.invoices.pdf') }}" class="dropdown-item" data-turbo="false">
            <i class="fas fa-file-pdf me-1"></i> {{ __('messages.pdf_export') }}
        </a>
    </ul>
</div>
<div class="my-3 my-sm-3">
    <a href="{{ route('invoices.create') }}" data-turbo="false"
        class="btn btn-primary">{{ __('messages.invoice.new_invoice') }}</a>
</div>
