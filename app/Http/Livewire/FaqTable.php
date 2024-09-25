<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FaqTable extends LivewireTableComponent
{
    protected $model = Faq::class;

    protected string $tableName = 'faqs';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'landing.faqs.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:10%;text-align:center',
                ];
            }

            return [
                'class' => 'w-75',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.faqs.question'), 'question')
                ->sortable()
                ->searchable()
                ->view('landing.faqs.components.question'),
            Column::make(__('messages.common.action'), 'id')
            ->format(function ($value, $row, Column $column) {
                return view('livewire.modal-action-button')->withValue(
                    [
                        'show-route' => $row->id,
                        'data-id' => $row->id,
                        'data-delete-id' => 'faq-delete-btn',
                        'data-edit-id' => 'faq-edit-btn',
                        'data-show-id' => 'faq-show-btn',
                    ]
                );
            }),
        ];
    }

    public function builder(): Builder
    {
        return Faq::query()->select('faqs.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('faqsPage');
    }
}
