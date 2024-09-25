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
        Schema::create('section_twos', function (Blueprint $table) {
            $table->id();
            $table->string('text_main');
            $table->text('text_secondary');
            $table->string('card_one_text');
            $table->text('card_one_text_secondary');
            $table->string('card_two_text');
            $table->text('card_two_text_secondary');
            $table->string('card_three_text');
            $table->string('card_three_text_secondary');
            $table->string('card_four_text');
            $table->string('card_four_text_secondary');
            $table->string('card_five_text');
            $table->string('card_five_text_secondary');
            $table->string('card_six_text');
            $table->string('card_six_text_secondary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_twos');
    }
};
