<?php

use App\Models\Currency;
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
        Schema::table('currencies', function (Blueprint $table) {
            $table->string('code')->nullable()->after('icon');
        });

        /** @var Currency $currenciesData */
        $currenciesData = Currency::whereNUll('code')->get();

        $currencies = file_get_contents(storage_path('currencies/defaultCurrency.json'));
        $currencies = json_decode($currencies, true)['currencies'];

        foreach ($currenciesData as $currency) {
            foreach ($currencies as $cur) {
                $currencyCode = $currency->name == $cur['name'] ? $cur['code'] : 'USD';
                $currency->update([
                    'code' => $currencyCode,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
