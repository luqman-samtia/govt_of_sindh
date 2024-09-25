<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', function ($table) {
            $table->string('model_type', 191)->change();
            $table->longText('manipulations')->change();
            $table->longText('custom_properties')->change();
            $table->longText('generated_conversions')->change();
            $table->longText('responsive_images')->change();
        });
        Schema::table('model_has_permissions', function ($table) {
            $table->string('model_type', 191)->change();
        });
        Schema::table('model_has_roles', function ($table) {
            $table->string('model_type', 191)->change();
        });
        Schema::table('password_resets', function ($table) {
            $table->string('email', 191)->change();
        });
        Schema::table('tenants', function ($table) {
            $table->longText('data')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function ($table) {
            $table->string('model_type', 255)->change();
            $table->json('manipulations')->change();
            $table->json('custom_properties')->change();
            $table->json('generated_conversions')->change();
            $table->json('responsive_images')->change();
        });
        Schema::table('model_has_permissions', function ($table) {
            $table->string('model_type', 255)->change();
        });
        Schema::table('model_has_roles', function ($table) {
            $table->string('model_type', 255)->change();
        });
        Schema::table('password_resets', function ($table) {
            $table->string('email', 255)->change();
        });
        Schema::table('tenants', function ($table) {
            $table->json('data')->change();
        });
    }
};
