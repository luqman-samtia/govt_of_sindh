<?php

namespace Database\Seeders;

use App\Models\SuperAdminSetting;
use Illuminate\Database\Seeder;

class AddVatNoLabelInSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = SuperAdminSetting::where('key', 'vat_no_label')->first();

        if (empty($setting)) {
            SuperAdminSetting::create(['key' => 'vat_no_label', 'value' => 'GSTIN']);
        }
    }
}
