@if ($row->payment_mode == 1)
    <span data-id="${row.payment_mode}"
        class="badge bg-light-success fs-7">{{ \App\Models\Transaction::PAYMENT_TYPES[$row->payment_mode] }}</span>
@elseif ($row->payment_mode == 2)
    <span data-id="${row.payment_mode}"
        class="badge bg-light-primary fs-7">{{ \App\Models\Transaction::PAYMENT_TYPES[$row->payment_mode] }}</span>
@elseif ($row->payment_mode == 3)
    <span data-id="${row.payment_mode}"
        class="badge bg-light-danger fs-7">{{ \App\Models\Transaction::PAYMENT_TYPES[$row->payment_mode] }}</span>
@elseif ($row->payment_mode == 4)
    <span data-id="${row.payment_mode}"
        class="badge bg-light-warning fs-7">{{ \App\Models\Transaction::PAYMENT_TYPES[$row->payment_mode] }}</span>
@elseif ($row->payment_mode == 5)
    <span data-id="${row.payment_mode}"
        class="badge bg-light-secondary fs-7">{{ \App\Models\Transaction::PAYMENT_TYPES[$row->payment_mode] }}</span>
@endif
