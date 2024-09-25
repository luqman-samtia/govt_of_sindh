<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Stancl\Tenancy\Database\Models\Tenant;

class ShowProductDescriptionKeyInInvoiceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminTenant = Tenant::where('tenant_username', 'admin')->first();

        if ($adminTenant) {
            $setting = Setting::where('key', 'show_product_description')->where('tenant_id', $adminTenant->id)->first();

            if (! $setting) {
                Setting::create([
                    'key' => 'show_product_description', 'value' => false,
                    'tenant_id' => $adminTenant->id,
                ]);
            }
        }
    }
}
