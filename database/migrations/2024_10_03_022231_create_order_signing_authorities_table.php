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
        Schema::create('order_signing_authorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('orders')->onDelete('cascade');
            $table->string('name');
            $table->string('designation');
            $table->string('department');
            $table->string('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_signing_authorities');
    }
};
