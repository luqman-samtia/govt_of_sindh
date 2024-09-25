<?php

namespace App\Http\Livewire;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class SubscriptionPlanTable extends LivewireTableComponent
{
    protected $model = SubscriptionPlan::class;

    protected string $tableName = 'subscription_plans';

    // for table header button

    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'subscription_plans.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setThAttributes(function (Column $column) {
            if (in_array($column->getTitle(), [
                __('messages.subscription_plans.active_plan'),
            ], true)) {
                return [
                    'class' => 'text-center',
                ];
            }
            if ($column->getTitle() == __('messages.common.action')) {
                return [
                    'style' => 'width:10%;text-align:center',
                ];
            }
            if ($column->getTitle() === __('messages.subscription_plans.make_default')) {
                return [
                    'class' => 'd-flex justify-content-center',
                ];
            }
            if ($column->isField('price')) {
                return [
                    'class' => 'd-flex justify-content-end',
                ];
            }

            return [];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex > 4) {
                return [
                    'class' => 'text-center',
                ];
            }
            if ($column->getField() === 'price') {
                return [
                    'class' => 'text-end',
                ];
            }

            return [
                'class' => 'text-left',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.subscription_plans.name'), 'name')
                ->sortable()->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()->hideIf(1),
            Column::make(__('messages.subscription_plans.price'), 'price')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return superAdminCurrencyAmount($row->price, false,
                        getAdminSubscriptionPlanCurrencyIcon($row->currency_id));
                }),
            Column::make(__('messages.subscription_plans.frequency'), 'frequency')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return $row->frequency == 1 ? 'Month' : 'Year';
                }),
            Column::make(__('messages.subscription_plans.trail_plan'), 'trial_days')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return $row->trial_days.' Days';
                }),
            Column::make(__('messages.subscription_plans.active_plan'), 'id')
                ->sortable()
                ->searchable()
                ->label(function ($row, Column $column) {
                    return '<span class="badge bg-light-info fs-7">'.$row->subscription->count().'</span>';
                })
                ->html(),
            Column::make(__('messages.subscription_plans.make_default'), 'is_default')
                ->sortable()
                ->searchable()
                ->view('subscription_plans.components.default'),
            Column::make(__('messages.common.action'), 'id')
                ->view('livewire.subscription-plan-action'),
        ];
    }

    public function builder(): Builder
    {
        $query = SubscriptionPlan::with(['subscription', 'currencies'])->select('subscription_plans.*')
            ->when($this->getAppliedFilterWithValue('frequency'), function ($query, $type) {
                return $query->where('frequency', $type);
            });

        return $query;
    }

    public function filters(): array
    {
        $planType = SubscriptionPlan::PLAN_TYPE;

        return [
            SelectFilter::make(__('messages.subscription_plans.plan_type').':')
                ->options($planType)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('frequency', '=', $value);
                }),
        ];
    }

    public function resetPageTable()
    {
        $this->customResetPage('subscription_plansPage');
    }
}
