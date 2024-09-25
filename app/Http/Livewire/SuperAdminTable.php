<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SuperAdminTable extends LivewireTableComponent
{
    protected $model = User::class;
    protected string $tableName = 'users';


    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'super_admin.table-components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:9%;text-align:center',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
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
            Column::make(__('messages.common.name'), 'first_name')
                ->sortable()
                ->searchable(function (Builder $query, $direction) {
                    $query->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                })
                ->view('super_admin.table-components.full-name'),
            Column::make(__('messages.user.email'), 'email')
                ->searchable()->hideIf(1),
                Column::make(__('designation'), 'designation')  // Adding Designation column
                ->searchable()
                ->sortable()
                ->view('super_admin.table-components.designation'),
                Column::make(__('Zone'), 'Zone')  // Adding Designation column
                ->searchable()
                ->sortable()
                ->view('super_admin.table-components.zone'),

            Column::make(__('district'), 'district')  // Adding District column
                ->searchable()
                ->sortable()
                ->view('super_admin.table-components.district'),

            Column::make(__('grade'), 'grade')  // Adding Grade column
                ->searchable()
                ->sortable()
                ->view('super_admin.table-components.grade'),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('super_admin.table-components.action-button')
                        ->withValue([
                            'edit-route' => route('super-admins.edit', $row->id),
                            'data-id' => $row->id,
                            'data-delete-id' => 'super-admin-delete-btn',
                        ]);
                }),

        ];
    }

    public function builder(): Builder
    {
        return User::where('id', '!=', Auth::id())->whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_SUPER_ADMIN);
        })->with(['roles', 'media'])->select('users.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('usersPage');
    }
}
