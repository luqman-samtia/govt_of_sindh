<?php

use App\Models\SuperAdminSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SuperAdminSetting::create([
            'key' => 'currency_after_amount',
            'value' => SuperAdminSetting::CURRENCY_AFTER_AMOUNT,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
