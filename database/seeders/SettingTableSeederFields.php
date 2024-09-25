<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeederFields extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTenantId = session('tenant_id', null);
        Setting::create([
            'key' => 'company_address',
            'value' => '',
            'tenant_id' => $userTenantId != null ? $userTenantId : null,
        ]);
        Setting::create(['key' => 'company_phone', 'value' => '',
            'tenant_id' => $userTenantId != null ? $userTenantId : null,
        ]);
    }
}
