<?php

namespace Database\Seeders;

use App\Models\SuperAdminSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultCookieWarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdminSetting::create(['key' => 'show_cookie','value' => 1]);
        SuperAdminSetting::create(['key' => 'cookie_warning','value' => 'Your experience on this site will be improved by allowing cookies.']);
    }
}
