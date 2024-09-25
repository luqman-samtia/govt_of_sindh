<?php

use App\Models\Setting;
use App\Models\User;
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
        Schema::table('settings', function (Blueprint $table) {
            $table->text('value')->nullable()->change();
        });

        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', \App\Models\Role::ROLE_ADMIN);
        })->get();

        /** @var User $admin */
        foreach ($admins as $admin) {
            $userTenantId = $admin->tenant_id;
            $invoiceNoPrefixExists = Setting::where('key', 'invoice_no_prefix')
                ->where('tenant_id', $userTenantId)->exists();
            if (! $invoiceNoPrefixExists) {
                Setting::create([
                    'key' => 'invoice_no_prefix',
                    'value' => null,
                    'tenant_id' => $userTenantId,
                ]);
            }

            $invoiceNoSuffixExists = Setting::where('key', 'invoice_no_suffix')
                ->where('tenant_id', $userTenantId)->exists();
            if (! $invoiceNoSuffixExists) {
                Setting::create([
                    'key' => 'invoice_no_suffix',
                    'value' => null,
                    'tenant_id' => $userTenantId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('value')->nullable(false)->change();
        });
    }
};
