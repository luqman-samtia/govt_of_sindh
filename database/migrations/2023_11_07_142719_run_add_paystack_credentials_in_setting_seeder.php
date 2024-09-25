<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'AddPaystackCredentialsInSettingSeeder',
            '--force' => true,
        ]);
    }
};
