<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Stancl\Tenancy\Database\TenantScope;

class ClientTable extends LivewireTableComponent
{
    protected $model = User::class;

    protected string $tableName = 'users';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'clients.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('page');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:9%',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->getField() === 'first_name') {
                return [
                    'class' => 'w-75',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.client.client'), 'first_name')
                ->searchable(function (Builder $query, $direction) {
                    $query->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                })
                ->sortable()
                ->view('clients.components.full_name'),
            Column::make(__('messages.client.client'), 'last_name')
                ->hideIf(1),
            Column::make('email', 'email')
                ->sortable()
                ->searchable()
                ->hideIf(1),
            Column::make(__('messages.client.invoice'), 'id')
                ->format(function ($value, $row, Column $column) {
                    $clientId = $row->clients->where('tenant_id', getLogInUser()->tenant_id)->first()->client_id;

                    return view('clients.components.invoice-count')
                        ->withValue([
                            'clientId' => $clientId,
                        ]);
                }),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    $clientTenant = $row->clients()->where('tenant_id', getLogInUser()->tenant_id)->first();
                    $clientId = $clientTenant->client_id;

                    return view('livewire.action-button')
                        ->withValue([
                            'edit-route' => route('clients.edit', $clientId),
                            'data-id' => $clientTenant->id,
                            'data-delete-id' => 'client-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        $tenantID = getLogInUser()->tenant_id;
        $query = User::with(['client', 'client.invoices', 'clients'])->whereHas('clients',
            function (Builder $q) use ($tenantID) {
                $q->where('tenant_id', $tenantID);
            })->withoutGlobalScope(new TenantScope());

        return $query;
    }

    public function resetPageTable()
    {
        $this->customResetPage('page');
    }
}
