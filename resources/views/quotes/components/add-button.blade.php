<div class="dropdown my-3 my-sm-3 me-2">
    <button class="btn btn-success text-white dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ __('messages.common.export') }}
    </button>
    <ul class="dropdown-menu export-dropdown">
        <a href="{{ route('admin.quotesExcel') }}" class="dropdown-item" data-turbo="false">
            <i class="fas fa-file-excel me-1"></i> {{ __('messages.invoice.excel_export') }}
        </a>
        <a href="{{ route('admin.quotes.pdf') }}" class="dropdown-item" data-turbo="false">
            <i class="fas fa-file-pdf me-1"></i> {{ __('messages.pdf_export') }}
        </a>
    </ul>
</div>
<div class="my-3 my-sm-3">
    <a href="{{ route('quotes.create') }}" data-turbo="false"
        class="btn btn-primary">{{ __('messages.quote.new_quote') }}</a>
</div>
