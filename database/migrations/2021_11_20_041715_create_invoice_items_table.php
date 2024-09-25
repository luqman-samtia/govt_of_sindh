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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')
                ->on('invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('product_name')->nullable();
            $table->integer('quantity');
            $table->float('tax')->nullable();
            $table->integer('tax_id')->nullable();
            $table->double('price', 8, 2);
            $table->double('total', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
