<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class InvoiceSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTenantId = session('tenant_id', null);
        Setting::create(['key' => 'default_invoice_template', 'value' => 'defaultTemplate',
            'tenant_id' => $userTenantId != null ? $userTenantId : null,
        ]);
        Setting::create(['key' => 'default_invoice_color', 'value' => '#040404',
            'tenant_id' => $userTenantId != null ? $userTenantId : null,
        ]);
        Setting::create([
            'key' => 'show_product_description', 'value' => false,
            'tenant_id' => $userTenantId != null ? $userTenantId : null,
        ]);
    }
}
