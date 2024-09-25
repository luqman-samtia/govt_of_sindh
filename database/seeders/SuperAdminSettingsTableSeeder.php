<?php

namespace Database\Seeders;

use App\Models\SuperAdminSetting;
use Illuminate\Database\Seeder;

class SuperAdminSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imageUrl = 'assets/images/infyom.png';
        $faviconImageUrl = 'images/favicon.png';

        SuperAdminSetting::create([
            'key' => 'app_name', 'value' => 'Invoice SaaS',
        ]);
        SuperAdminSetting::create([
            'key' => 'app_logo', 'value' => $imageUrl,
        ]);
        SuperAdminSetting::create([
            'key' => 'favicon_icon',
            'value' => $faviconImageUrl,
        ]);
    }
}
