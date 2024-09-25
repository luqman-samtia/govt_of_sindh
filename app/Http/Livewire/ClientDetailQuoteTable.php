<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClientDetailQuoteTable extends LivewireTableComponent
{
    protected $model = Quote::class;

    public $clientId = null;

    public function mount(int $clientId): void
    {
        $this->clientId = $clientId;
    }

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
            if ($column->isField('status')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['status'])) {
                return [
                    'class' => 'text-center',
                ];
            }
            if ($column->getField() == 'final_amount') {
                return [
                    'class' => 'text-end',
                ];
            }

            return [
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.quote.quote_id'), 'id')
                ->sortable()
                ->searchable()
                ->view('clients.quote.components.quote-id'),
            Column::make('quote_id', 'quote_id')
                ->sortable()
                ->searchable()->hideIf(1),
            Column::make(__('messages.quote.quote_date'), 'quote_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('quotes.components.quote-due-date')
                        ->withValue([
                            'quote-date' => $row->quote_date,
                        ]);
                }),
            Column::make(__('messages.quote.due_date'), 'due_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('quotes.components.quote-due-date')
                        ->withValue([
                            'due-date' => $row->due_date,
                        ]);
                }),
            Column::make(__('messages.quote.amount'), 'final_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getCurrencyAmount($row->final_amount, true);
                }),
            Column::make(__('messages.common.status'), 'status')
                ->searchable()
                ->view('quotes.components.quote-status'),
        ];
    }

    public function builder(): Builder
    {
        return Quote::where('client_id', $this->clientId);
    }

    public function resetPageTable()
    {
        $this->customResetPage('page');
    }
}
