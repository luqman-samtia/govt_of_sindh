<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class DefaultSubscriptionPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            'name' => 'Standard',
            'currency' => 'usd',
            'price' => 10,
            'frequency' => SubscriptionPlan::MONTH,
            'is_default' => 1,
            'trial_days' => 7,
            'currency_id' => 4,
        ];

        $subscriptionPlan = SubscriptionPlan::where('name', $input['name'])->exists();
        if (!$subscriptionPlan) {
            $subscriptionPlan = SubscriptionPlan::create($input);
        }
    }
}
