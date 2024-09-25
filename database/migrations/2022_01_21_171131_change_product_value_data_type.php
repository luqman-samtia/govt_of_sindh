<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->float('unit_price')->change();
        });
        Schema::table('taxes', function (Blueprint $table) {
            $table->float('value')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit_price')->change();
        });
        Schema::table('taxes', function (Blueprint $table) {
            $table->string('value')->change();
        });
    }
};
