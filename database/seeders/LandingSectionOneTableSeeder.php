<?php

namespace Database\Seeders;

use App\Models\SectionOne;
use Illuminate\Database\Seeder;

class LandingSectionOneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            'text_main' => 'Free invoicing software for small businesses',
            'text_secondary' => 'Infy Invoice is online invoicing software that helps you craft professional invoices, send payment reminders, keep track of expenses, log your work hours, and get paid faster—all for free!',
            'img_url_one' => ('/landing-page/images/hero-image.png'),
        ];

        SectionOne::create($input);
    }
}
