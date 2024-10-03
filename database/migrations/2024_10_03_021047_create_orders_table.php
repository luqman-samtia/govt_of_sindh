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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Change to unsignedBigInteger
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('letter_no');
            $table->string('qr_code');
            $table->string('qr_code_link');
            $table->string('signed_letter');
            $table->string('pdf_hash');
            $table->string('head_title');
            $table->string('fix_address');
            $table->date('date');
            $table->text('subject');
            $table->string('dear')->nullable();
            $table->longText('draft_para');
            $table->boolean('is_submitted')->default(0); // to track if submitted or draft
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
