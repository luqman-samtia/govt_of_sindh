<?php

use App\Repositories\SettingRepository;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admins = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', \App\Models\Role::ROLE_ADMIN);
        })->get();

        /** @var \App\Models\User $admin */
        foreach ($admins as $admin) {
            $userTenantId = $admin->tenant_id;
            $settingExists = \App\Models\Setting::where('key', 'payment_auto_approved')->where('tenant_id',
                $userTenantId)->exists();
            if (! $settingExists) {
                /** @var SettingRepository $settingRepo */
                $settingRepo = \Illuminate\Support\Facades\App::make(SettingRepository::class);
                $settingRepo->addPaymentAutoApproveSetting($userTenantId);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
