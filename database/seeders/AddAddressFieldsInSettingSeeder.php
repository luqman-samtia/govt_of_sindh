<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddAddressFieldsInSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::role('admin')->first();

        if(!empty($user)) {
            $tenantId = $user->tenant_id;
            $settings = [
                ['key' => 'city', 'value' => 'Surat', 'tenant_id' => $tenantId],
                ['key' => 'state', 'value' => 'Gujarat', 'tenant_id' => $tenantId],
                ['key' => 'country', 'value' => 'India', 'tenant_id' => $tenantId],
                ['key' => 'zipcode', 'value' => '394101', 'tenant_id' => $tenantId],
                ['key' => 'fax_no', 'value' => '555-123-4567', 'tenant_id' => $tenantId],
                ['key' => 'show_additional_address_in_invoice', 'value' => 0, 'tenant_id' => $tenantId],
            ];
            foreach ($settings as $setting) {
                Setting::create($setting);
            }
        }
    }
}
