<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClientInvoiceTable extends LivewireTableComponent
{
    protected $model = Invoice::class;

    protected string $tableName = 'invoices';

    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'client_panel.invoices.components.filters_with_export';

    public $listeners = ['resetPageTable','changeStatusFilter','changeDateRangeFilter'];

    public string $status = '';

    public array $dateRange = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('final_amount')) {
                return [
                    'class' => 'd-flex justify-content-end',
                ];
            }
            if ($column->isField('amount')) {
                return [
                    'class' => 'text-end',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['status', 'id'])) {
                return [
                    'class' => 'text-center',
                ];
            }
            if (in_array($column->getField(), ['final_amount', 'amount'])) {
                return [
                    'class' => 'text-end',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.invoice.invoice_id'), 'invoice_id')
                ->sortable()
                ->searchable()
                ->view('client_panel.invoices.components.invoice-id'),
            Column::make('invoice_id', 'invoice_id')
                ->sortable()
                ->searchable()->hideIf(1),
            Column::make('Last Name', 'client.user.last_name')
                ->sortable()
                ->searchable()->hideIf(1),
            Column::make(__('messages.invoice.invoice_date'), 'invoice_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('invoices.components.invoice-due-date')
                        ->withValue([
                            'invoice-date' => $row->invoice_date,
                        ]);
                }),
            Column::make(__('messages.invoice.due_date'), 'due_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('invoices.components.invoice-due-date')
                        ->withValue([
                            'due-date' => $row->due_date,
                        ]);
                }),
            Column::make(__('messages.invoice.amount'), 'final_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getInvoiceCurrencyAmount($row->final_amount, $row->currency_id, true);
                }),
            Column::make(__('messages.invoice.transactions'), 'amount')
                ->searchable()
                ->view('invoices.components.transaction'),
            Column::make(__('messages.common.status'), 'status')
                ->searchable()
                ->view('invoices.components.transaction-status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('livewire.client-invoice-action-button'),

        ];
    }

    public function builder(): Builder
    {
        $clientId = Auth::user()->client->id;
        $query = Invoice::with(['client.user', 'payments'])->where('invoices.client_id', $clientId)
            ->where('invoices.status', '!=', Invoice::DRAFT);

        $query->when($this->status != '', function ($query) {
            $query->where('invoices.status', $this->status);
        });

        $query->when(count($this->dateRange) > 0, function ($query) {
            $startDate = $this->dateRange[0];
            $endDate = $this->dateRange[1];
            $query->whereBetween('invoices.invoice_date', [$startDate, $endDate]);
        });

        return $query->select('invoices.*');
    }

    public function changeStatusFilter($status)
    {
        $this->status = $status;
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
        $this->customResetPage('page');
    }

    public function resetPagination()
    {
        $this->resetPage('invoicesPage');
    }
}
