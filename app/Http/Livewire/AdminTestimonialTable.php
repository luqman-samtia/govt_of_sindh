<?php

namespace App\Http\Livewire;

use App\Models\AdminTestimonial;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class AdminTestimonialTable extends LivewireTableComponent
{
    protected $model = AdminTestimonial::class;

    protected string $tableName = 'admin_testimonials';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'landing.testimonial.components.add-button';

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

            return [];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($column->getField(), ['id'])) {
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
            ImageColumn::make(__('messages.profile'))
                ->location(function ($row) {
                    return $row->image_url;
                })
                ->attributes(function ($row) {
                    $data = ['class' => 'user-img image image-circle image-mini me-3',
                        'alt' => $row->name, 'width' => '50px', 'height' => '50px', ];

                    return $data;
                }),
            Column::make(__('messages.testimonial.name'), 'name')
                ->sortable()
                ->searchable()
                ->view('landing.testimonial.components.name'),
            Column::make(__('messages.testimonial.designation'), 'position')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->withValue([
                            'show-route' => $row->id,
                            'data-id' => $row->id,
                            'data-delete-id' => 'testimonial-delete-btn',
                            'data-edit-id' => 'testimonial-edit-btn',
                            'data-show-id' => 'testimonial-show-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        return AdminTestimonial::with('media')->select('admin_testimonials.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('admin_testimonialsPage');
    }
}
