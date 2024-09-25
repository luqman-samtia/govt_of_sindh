<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', \App\Models\Role::ROLE_ADMIN);
        })->get();

        /** @var User $admin */
        foreach ($admins as $admin) {
            $userTenantId = $admin->tenant_id;

            $keys = [
                'stripe_key',
                'stripe_secret',
                'paypal_client_id',
                'paypal_secret',
                'razorpay_key',
                'razorpay_secret',
            ];

            foreach ($keys as $key) {
                Setting::create([
                    'key' => $key,
                    'value' => '',
                    'tenant_id' => $userTenantId,
                ]);
            }
            Setting::create([
                'key' => 'stripe_enabled',
                'value' => 0,
                'tenant_id' => $userTenantId,
            ]);
            Setting::create([
                'key' => 'paypal_enabled',
                'value' => 0,
                'tenant_id' => $userTenantId,
            ]);
            Setting::create([
                'key' => 'razorpay_enabled',
                'value' => 0,
                'tenant_id' => $userTenantId,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
