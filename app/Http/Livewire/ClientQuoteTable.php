<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ClientQuoteTable extends LivewireTableComponent
{
    protected $model = Quote::class;

    protected string $tableName = 'quotes';

    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'client_panel.quotes.components.add-button';

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

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['amount', 'status', 'id'])) {
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
            Column::make(__('messages.quote.quote_id'), 'quote_id')
                ->sortable()
                ->searchable()
                ->view('client_panel.quotes.components.quote-id'),
            Column::make('quote_id', 'quote_id')
                ->sortable()
                ->searchable()->hideIf(1),
            Column::make('Last Name', 'client.user.last_name')
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
            Column::make(__('messages.common.action'), 'id')
                ->view('livewire.client-quote-action-button'),
        ];
    }

    public function builder(): Builder
    {
        $status = request()->input('status', null);
        $clientId = Auth::user()->client->id;

        $query = Quote::with(['client.user.media'])->select('quotes.*')->where('client_id', $clientId)
            ->when($status, function ($query, $status) {
                return $query->where('quotes.status', $status);
            })
            ->when($this->getAppliedFilterWithValue('quotes.status'), function ($query, $type) {
                return $query->where('quotes.status', $type);
            });

        return $query;
    }

    public function filters(): array
    {
        $status = Quote::STATUS_ARR;
        unset($status[Quote::STATUS_ALL]);

        return [
            SelectFilter::make(__('messages.common.status').':')
                ->options($status)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('quotes.status', '=', $value);
                }),
        ];
    }

    public function resetPageTable()
    {
        $this->customResetPage('page');
    }
}
