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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->decimal('final_amount', 15, 2)->nullable()->change();
        });
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->change();
            $table->decimal('total', 15, 2)->nullable()->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->decimal('final_amount', 15, 2)->nullable()->change();
        });
        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->change();
            $table->decimal('total', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
