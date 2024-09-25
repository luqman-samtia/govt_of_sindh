<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CategoryTable extends LivewireTableComponent
{
    protected $model = Category::class;

    protected string $tableName = 'categories';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'category.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('page');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['name'])) {
                return [
                    'class' => 'w-50',
                ];
            }
            if ($columnIndex == '1') {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:9%;text-align:center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.category.category'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.product.product'), 'id')
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return $row->products_count;
                }),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->withValue([
                            'data-id' => $row->id,
                            'data-delete-id' => 'category-delete-btn',
                            'data-edit-id' => 'category-edit-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return Category::withCount('products');
    }

    public function resetPageTable()
    {
        $this->customResetPage('categoriesPage');
    }
}
