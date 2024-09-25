<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProductTable extends LivewireTableComponent
{
    protected $model = Product::class;

    protected string $tableName = 'products';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'products.components.add-button';

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

            if ($column->getField() == 'unit_price') {
                return [
                    'class' => 'd-flex justify-content-end',
                ];
            }

            return [];
        });
        $this->setTdAttributes(function (Column $column) {
            if ($column->getField() === 'id') {
                return [
                    'class' => 'text-center',
                ];
            }
            if ($column->getField() === 'unit_price') {
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
            Column::make(__('messages.product.product_name'), 'name')
                ->sortable()
                ->searchable()
                ->view('products.components.product-name'),
            Column::make(__('messages.product.generate_code'), 'code')->searchable()->hideIf(1),
            Column::make(__('messages.product.category'), 'category.name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(Category::select('name')->whereColumn('categories.id', 'category_id'),
                        $direction);
                })
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return $row->category->name;
                }),
            Column::make(__('messages.product.price'), 'unit_price')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getCurrencyAmount($row->unit_price, 'true');
                }),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.action-button')
                        ->withValue([
                            'edit-route' => route('products.edit', $row->id),
                            'data-id' => $row->id,
                            'data-delete-id' => 'product-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return Product::with(['category','media'])->select('products.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('productsPage');
    }
}
