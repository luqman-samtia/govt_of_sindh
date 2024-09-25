<?php

namespace App\Repositories;

use App\Models\SubscriptionPlan;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'valid_until',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SubscriptionPlan::class;
    }

    public function store($input)
    {
        $subscriptionPlan = null;
        $input['trial_days'] = $input['trial_days'] != null ? $input['trial_days'] : 0;
        $input['price'] = removeCommaFromNumbers($input['price']);
        /** @var SubscriptionPlan $subscriptionPlan */
        $subscriptionPlan = SubscriptionPlan::create(Arr::except($input, ['plan_feature']));

        return $subscriptionPlan;
    }

    public function update($input, $id)
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        $input['trial_days'] = $input['trial_days'] != null ? $input['trial_days'] : 0;
        $input['price'] = removeCommaFromNumbers($input['price']);
        $subscriptionPlan->update($input);

        return $subscriptionPlan;
    }

    public function getSubscriptionPlansData(): array
    {
        $data = null;
        $subscriptionPlanData = SubscriptionPlan::with(['plan', 'plans', 'subscription', 'hasZeroPlan'])->get();

        $data['subscriptionPricingMonthPlans'] = $subscriptionPlanData->where('frequency', '=', SubscriptionPlan::MONTH);
        $data['subscriptionPricingYearPlans'] = $subscriptionPlanData->where('frequency', '=', SubscriptionPlan::YEAR);

        return $data;
    }
}
