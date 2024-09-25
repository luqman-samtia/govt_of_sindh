<?php

namespace App\Http\Livewire;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AccountTable extends LivewireTableComponent
{
    protected $model = Account::class;

    protected string $tableName = 'accounts';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'accounts.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('address')) {
                return [
                    'class' => 'w-25',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['bank_branch', 'balance'])) {
                return [
                    'class' => '',
                ];
            }
            if ($column->getField() == 'balance') {
                return [
                    'class' => '',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.accounts.holder_name'), 'holder_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.accounts.bank_name'), 'bank_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.accounts.account_number'), 'account_number')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.accounts.balance'), 'balance')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return number_format($row->balance, 2).' '.getCurrencySymbol();
                }),
            Column::make(__('messages.accounts.bank_branch'), 'address')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->withValue([
                            'data-id' => $row->id,
                            'data-delete-id' => 'account-delete-btn',
                            'data-edit-id' => 'account-edit-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return Account::query()->select('accounts.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('accountsPage');
    }
}
