<?php

namespace App\Http\Livewire;

use App\Models\AdminCurrency;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AdminCurrencyTable extends LivewireTableComponent
{
    protected $model = AdminCurrency::class;

    protected string $tableName = 'admin_currencies';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'super_admin.currencies.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width:10%;text-align:center',
                ];
            }
            if ($column->isField('icon')) {
                return [
                    'class' => 'w-25',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.currency.icon'), 'icon')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.currency.currency_code'), 'code')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->withValue([
                            'data-id' => $row->id,
                            'data-delete-id' => 'admin-currency-delete-btn',
                            'data-edit-id' => 'admin-currency-edit-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return AdminCurrency::query()->select('admin_currencies.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('admin_currenciesPage');
    }
}
