<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call(DefaultPermissionSeeder::class);
        $this->call(DefaultCountriesSeeder::class);
        $this->call(DefaultCurrencySeeder::class);
        $this->call(SuperAdminSettingsTableSeeder::class);
        $this->call(SuperAdminFooterSettingsSeeder::class);
        $this->call(LandingSectionOneTableSeeder::class);
        $this->call(LandingSectionTwoTableSeeder::class);
        $this->call(LandingSectionThreeTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(AdminTestimonialSeeder::class);
    }
}
