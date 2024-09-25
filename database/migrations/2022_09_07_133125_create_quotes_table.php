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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')
                ->on('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('quote_date');
            $table->date('due_date');
            $table->float('amount')->nullable();
            $table->float('final_amount')->nullable();
            $table->integer('discount_type')->default(0);
            $table->float('discount')->default(0);
            $table->text('note')->nullable();
            $table->text('term')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')
                ->on('invoice-settings')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('recurring')->default(0);
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
