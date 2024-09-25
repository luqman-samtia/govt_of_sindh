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
        $keys = [
            'stripe_key',
            'stripe_secret',
            'paypal_client_id',
            'paypal_secret',
            'razorpay_key',
            'razorpay_secret',
        ];

        foreach ($keys as $key) {
            SuperAdminSetting::create([
                'key' => $key,
                'value' => '',
            ]);
        }
        SuperAdminSetting::create([
            'key' => 'stripe_enabled',
            'value' => 0,
        ]);
        SuperAdminSetting::create([
            'key' => 'paypal_enabled',
            'value' => 0,
        ]);
        SuperAdminSetting::create([
            'key' => 'razorpay_enabled',
            'value' => 0,
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
