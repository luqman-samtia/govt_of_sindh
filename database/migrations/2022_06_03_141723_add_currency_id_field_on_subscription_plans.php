<?php

use App\Models\AdminCurrency;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable()->after('currency');
            $table->foreign('currency_id')
                ->references('id')
                ->on('admin_currencies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        $adminCurrencies = AdminCurrency::get();
        $adminCurrenciesCode = AdminCurrency::toBase()->pluck('code')->toArray();

        $subscriptionPlanDatas = SubscriptionPlan::whereNotIn('currency', $adminCurrenciesCode)->get();
        if ($subscriptionPlanDatas) {
            foreach ($subscriptionPlanDatas as $subscriptionPlanData) {
                $subscriptionPlanData->delete();
            }
        }

        foreach ($adminCurrencies as $adminCurrency) {
            $subscriptionPlans = SubscriptionPlan::whereCurrency($adminCurrency->code)->get();
            foreach ($subscriptionPlans as $subscriptionPlan) {
                $subscriptionPlan->update([
                    'currency_id' => $adminCurrency->id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('currency_id');
        });
    }
};
