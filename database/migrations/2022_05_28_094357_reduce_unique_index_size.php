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
        Schema::table('countries', function ($table) {
            $table->string('name', 161)->change();
            $table->string('short_code', 161)->change();
        });
        Schema::table('domains', function ($table) {
            $table->string('domain', 161)->change();
        });
        Schema::table('failed_jobs', function ($table) {
            $table->string('uuid', 161)->change();
        });
        Schema::table('media', function ($table) {
            $table->uuid('uuid', 161)->change();
        });
        Schema::table('users', function ($table) {
            $table->string('email', 161)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function ($table) {
            $table->string('name', 170)->change();
            $table->string('short_code', 170)->change();
        });
        Schema::table('domains', function ($table) {
            $table->string('domain', 171)->change();
        });
        Schema::table('failed_jobs', function ($table) {
            $table->string('uuid', 171)->change();
        });
        Schema::table('media', function ($table) {
            $table->uuid('uuid', 171)->change();
        });
        Schema::table('users', function ($table) {
            $table->string('email', 171)->change();
        });
    }
};
