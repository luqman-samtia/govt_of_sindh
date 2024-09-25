<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TransactionTable extends LivewireTableComponent
{
    protected $model = Payment::class;

    protected string $tableName = 'payments';

    protected $listeners = ['changePaymentModeFilter', 'changePaymentStatusFilter', 'resetPageTable','changeDateRangeFilter'];

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'transactions.components.filters_with_export';

    public string $paymentMode = '';

    public string $paymentStatus = '';

    public array $dateRange = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('payment_mode')) {
                return [
                    'class' => 'text-center w-12',
                ];
            }
            if ($column->isField('amount')) {
                return [
                    'class' => 'd-flex justify-content-end',
                ];
            }
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
        $this->setTdAttributes(function (Column $column) {
            if ($column->getField() === 'payment_mode') {
                return [
                    'class' => 'text-center',
                ];
            }
            if ($column->getField() === 'amount') {
                return [
                    'class' => 'text-end',
                ];
            }
            if ($column->getField() === 'id') {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.payment.transaction_id'), 'transaction_id')
                ->searchable()
                ->view('transactions.components.transactions-id'),
            Column::make(__('messages.invoice.invoice_id'), 'invoice.invoice_id')
                ->searchable(function (Builder $query, $invoiceID) {
                    return $query->orWhere('invoices.invoice_id', 'like', '%'.trim($invoiceID).'%');
                })
                ->format(function ($value, $row, Column $column) {
                    return view('transactions.components.invoice-id-payment-date')
                        ->withValue([
                            'invoice-id-route' => route('invoices.show', $row->invoice->id),
                            'invoice-id' => $row->invoice->invoice_id,
                        ]);
                }),
            Column::make(__('messages.invoice.client'), 'invoice.client.user.first_name')
                //                ->sortable()
                ->searchable()
                ->view('transactions.components.client-name'),
            Column::make('Last Name', 'invoice.client.user.last_name')
                ->sortable()
                ->searchable()
                ->hideif('admin'),
            Column::make(__('messages.payment.payment_date'), 'payment_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('transactions.components.invoice-id-payment-date')
                        ->withValue([
                            'payment-date' => $row->payment_date,
                        ]);
                }),
            Column::make(__('messages.invoice.amount'), 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getinvoiceCurrencyAmount($row->amount, $row->invoice->currency_id, true);
                }),
            Column::make(__('messages.subscription_plans.payment_approved'), 'id')
                ->searchable()
                ->view('transactions.components.transaction-approved'),
            Column::make(__('messages.invoice.payment_method'), 'payment_mode')
                ->searchable()
                ->view('transactions.components.payment-mode'),
            Column::make(__('messages.common.status'), 'payment_mode')
                ->searchable()
                ->view('transactions.components.transaction-status'),
            Column::make(__('messages.common.attachment'), 'id')
                ->searchable()
                ->view('transactions.components.transaction-attachment'),
        ];
    }

    public function builder(): Builder
    {
        $query = Payment::with('invoice.client.user');

        $query->when($this->paymentMode != '', function (Builder $q) {
            $q->where('payment_mode', $this->paymentMode);
        });

        $query->when($this->paymentStatus != '', function (Builder $q) {
            $q->where('is_approved', $this->paymentStatus);
        });

        $query->when($this->dateRange != [], function (Builder $q) {
            $startDate = $this->dateRange[0];
            $endDate = $this->dateRange[1];
            $q->whereBetween('payment_date', [$startDate, $endDate]);
        });

        return $query->select('payments.*');
    }

    public function changePaymentModeFilter($mode)
    {
        $this->paymentMode = $mode;
        $this->setBuilder($this->builder());
    }

    public function changePaymentStatusFilter($status)
    {
        $this->paymentStatus = $status;
        $this->setBuilder($this->builder());
    }

    public function changeDateRangeFilter($dateRange)
    {
        $this->dateRange = $dateRange;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPageTable()
    {
        $this->customResetPage('paymentsPage');
    }

    public function resetPagination()
    {
        $this->resetPage('paymentsPage');
    }
}
