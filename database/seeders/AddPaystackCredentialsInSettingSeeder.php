<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SuperAdminSetting;
use Illuminate\Database\Seeder;

class AddPaystackCredentialsInSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for admin setting
        $adminTenantId = session('tenant_id', null);
        Setting::create(['key' => 'paystack_key', 'value' => '','tenant_id' =>  $adminTenantId]);
        Setting::create(['key' => 'paystack_secret', 'value' => '','tenant_id' => $adminTenantId]);
        Setting::create(['key' => 'paystack_enabled', 'value' => 0,'tenant_id' => $adminTenantId]);

        // for super admin setting
        SuperAdminSetting::create(['key' => 'paystack_key', 'value' => '']);
        SuperAdminSetting::create(['key' => 'paystack_secret', 'value' => '']);
        SuperAdminSetting::create(['key' => 'paystack_enabled', 'value' => 0]);
    }
}
