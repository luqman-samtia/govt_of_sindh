<?php

namespace Database\Seeders;

use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class NewUserSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageUrl = 'assets/images/infyom.png';
        $faviconImageUrl = 'images/favicon.png';

        UserSetting::create([
            'key' => 'app_name', 'value' => 'Invoice SaaS',
        ]);
        UserSetting::create([
            'key' => 'app_logo', 'value' => $imageUrl,
        ]);
        UserSetting::create([
            'key' => 'company_name', 'value' => 'InfyOm',
        ]);
        UserSetting::create([
            'key' => 'country_code', 'value' => 'in',
        ]);
        UserSetting::create([
            'key' => 'company_phone', 'value' => '9696858565',
        ]);
        UserSetting::create([
            'key' => 'date_format', 'value' => 'Y.m.d',
        ]);
        UserSetting::create([
            'key' => 'time_zone', 'value' => 'Asia/Kolkata',
        ]);
        UserSetting::create([
            'key' => 'favicon_icon',
            'value' => $faviconImageUrl,
        ]);
    }
}
