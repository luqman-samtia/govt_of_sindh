<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentHistoryTable extends LivewireTableComponent
{
    protected $model = Payment::class;

    protected string $tableName = 'payments';

    // for table header button
    public bool $showButtonOnHeader = true;
    public string $buttonComponent = 'transactions.components.make-payment-button';

    public $invoiceId = null;

    public function mount(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('amount')) {
                return [
                    'class' => 'd-flex justify-content-end',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['payment_date'])) {
                return [
                    'class' => 'w-25',
                ];
            }
            if ($column->getField() == 'amount') {
                return [
                    'class' => 'text-end',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.payment.payment_date'), 'payment_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('transactions.components.invoice-id-payment-date')
                        ->withValue([
                            'payment-date' => $row->payment_date,
                        ]);
                }),
            Column::make(__('messages.invoice.paid_amount'), 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getInvoiceCurrencyAmount($row->amount, $row->invoice->currency_id, true);
                }),
            Column::make(__('messages.payment.payment_mode'), 'payment_mode')
                ->searchable()
                ->view('transactions.components.payment-mode'),
            Column::make(__('messages.common.status'), 'is_approved')
                ->searchable()
                ->view('transactions.components.transaction-status'),
        ];
    }

    public function builder(): Builder
    {
        $query = Payment::where('invoice_id', $this->invoiceId)->with('invoice')->select('payments.*');

        return $query;
    }

    public function resetPageTable()
    {
        $this->customResetPage('paymentsPage');
    }
}
