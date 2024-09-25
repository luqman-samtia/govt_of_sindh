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
            $table->dropColumn('recurring');
            $table->foreignId('parent_id')
                ->nullable()
                ->after('invoice_id')
                ->constrained('invoices')
                ->restrictOnUpdate()->nullOnDelete();
            $table->boolean('recurring_status')->default(false)->after('status');
            $table->integer('recurring_cycle')->nullable()->after('recurring_status');
            $table->date('last_recurring_on')->nullable()->after('recurring_cycle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('recurring_status');
            $table->dropColumn('recurring_cycle');
            $table->dropColumn('last_recurring_on');
            $table->integer('recurring')->default(0)->after('template_id');
        });
    }
};
