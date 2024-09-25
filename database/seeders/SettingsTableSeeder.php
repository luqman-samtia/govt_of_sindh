<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTenantId = session('tenant_id', null);
        $imageUrl = 'assets/images/infyom.png';

        Setting::create([
            'key' => 'app_name', 'value' => '',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'app_logo', 'value' => '',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'company_name', 'value' => '',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'company_logo', 'value' => '',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'date_format', 'value' => 'Y.m.d',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'time_format', 'value' => '0',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'time_zone', 'value' => '',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'current_currency', 'value' => '3',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'decimal_separator', 'value' => '.',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'thousand_separator', 'value' => ',',
            'tenant_id' => $userTenantId ?? null,
        ]);
        Setting::create([
            'key' => 'mail_notification', 'value' => '1',
            'tenant_id' => $userTenantId ?? null,
        ]);
    }
}
