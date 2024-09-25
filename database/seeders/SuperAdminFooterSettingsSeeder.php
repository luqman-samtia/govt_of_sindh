<?php

namespace Database\Seeders;

use App\Models\SuperAdminSetting;
use Illuminate\Database\Seeder;

class SuperAdminFooterSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inputs = [
            [
                'key' => 'plan_expire_notification',
                'value' => '6',
            ],
            [
                'key' => 'footer_text',
                'value' => 'Over past 10+ years of experience and skills in various technologies, we built great scalable products. Whatever technology we worked with, we just not build products for our clients but we a',
            ],
            [
                'key' => 'address',
                'value' => 'C-303, Atlanta Shopping Mall, Nr. Sudama Chowk, Mota Varachha, Surat - 394101, Gujarat, India.',
            ],
            [
                'key' => 'email',
                'value' => 'contact@infyom.in',
            ],
            [
                'key' => 'phone',
                'value' => '+91 70963 36561',
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://www.facebook.com/infyom/',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/infyom?lang=en',
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://www.youtube.com/?hl=en',
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://www.linkedin.com/organization-guest/company/infyom-technologies?challengeId=AQFgQaMxwSxCdAAAAXOA_wosiB2vYdQEoITs6w676AzV8cu8OzhnWEBNUQ7LCG4vds5-A12UIQk1M4aWfKmn6iM58OFJbpoRiA&submissionId=0088318b-13b3-2416-9933-b463017e531e',
            ],
        ];

        foreach ($inputs as $input) {
            SuperAdminSetting::create($input);
        }
    }
}
