<?php

use Database\Seeders\AddVatNoLabelInSettingSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('db:seed', [
            '--class' => AddVatNoLabelInSettingSeeder::class,
            '--force' => true,
        ]);
    }
};
