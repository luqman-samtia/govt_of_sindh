<?php

use App\Models\SuperAdminSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('super_admin_settings', function (Blueprint $table) {
            SuperAdminSetting::create([
                'key' => 'home_page_support_link',
                'value' => null,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('super_admin_settings', function (Blueprint $table) {
            //
        });
    }
};
