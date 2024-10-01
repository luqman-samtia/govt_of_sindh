<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTable extends LivewireTableComponent
{
    protected $model = User::class;

    protected string $tableName = 'users';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'users.table-components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:9%;text-align:center',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '6' || $columnIndex == '5' || $columnIndex == '4' || $columnIndex == '3') {
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
            Column::make(__('messages.user.full_name'), 'first_name')
                ->searchable(function (Builder $query, $direction) {
                    $query->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                })
                ->sortable()
                ->view('users.table-components.full-name'),
            Column::make(__('messages.user.email'), 'email')
                ->searchable()->hideIf(1),
            Column::make(__('messages.client.role'), 'id')
                ->sortable()
                ->searchable()
                ->view('users.table-components.my-role'),
                Column::make(__('designation'), 'designation')  // Adding Designation column
                ->searchable()
                ->sortable()
                ->view('users.table-components.designation'),
                Column::make(__('Zone'), 'Zone')  // Adding Designation column
                ->searchable()
                ->sortable()
                ->view('users.table-components.zone'),

            Column::make(__('district'), 'district')  // Adding District column
                ->searchable()
                ->sortable()
                ->view('users.table-components.district'),
            Column::make(__('letter_no'), 'letter_no')  // Adding District column
                ->searchable()
                ->sortable()
                ->view('users.table-components.letter_no'),

            Column::make(__('grade'), 'grade')  // Adding Grade column
                ->searchable()
                ->sortable()
                ->view('users.table-components.grade'),
            Column::make(__('messages.user.email_verified'), 'email_verified_at')
                ->view('users.table-components.email-verified'),
            Column::make(__('messages.common.status'), 'status')
                ->searchable()
                ->view('users.table-components.my-status'),
            // Column::make(__('messages.impersonate'), 'id')
            //     ->view('users.table-components.impersonate'),

            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.action-button')
                        ->withValue([
                            'edit-route' => route('users.edit', $row->id),
                            'data-id' => $row->id,
                            'data-delete-id' => 'user-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return User::with(['roles', 'media'])->whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->select('users.*', 'designation', 'district', 'grade');
    }

    public function resetPageTable()
    {
        $this->customResetPage('usersPage');
    }
}
