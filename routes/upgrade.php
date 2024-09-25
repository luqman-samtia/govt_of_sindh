<?php

use App\Models\Role;
use App\Models\User;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

//upgrade To V2.0.0
Route::get('/upgrade-to-v2-0-0', function () {
    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_04_27_185521_refactor_tenant_id_on_all_tables.php',
    ]);
});

//upgrade To V2.5.0
Route::get('/upgrade-to-v2-5-0', function () {
    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_05_10_120640_add_field_no_of_user_limit_on_subscription_plans.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_05_11_160131_reduce_size_of_index_columns.php',
    ]);
});

//upgrade To V3.0.0
Route::get('/upgrade-to-v3-0-0', function () {
    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_05_19_102113_reduce_length_of_index_column.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_05_28_094357_reduce_unique_index_size.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_05_30_165457_create_admin_currencies_table.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_06_02_122226_add_is_manual_payment_to_transactions__table.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_06_03_141723_add_currency_id_field_on_subscription_plans.php',
    ]);
});

//upgrade To V4.0.0
Route::get('/upgrade-to-v4-0-0', function () {
    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_07_21_115425_add_home_page_support_link_to_super_admin_settings.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_07_21_125531_change_email_and_password_to_users.php',
    ]);
});

//upgrade To V4.2.0
Route::get('/upgrade-to-v4-2-0', function () {
    $admins = User::whereHas('roles', function ($query) {
        $query->where('name', Role::ROLE_ADMIN);
    })->get();

    /** @var User $admin */
    foreach ($admins as $admin) {
        $userTenantId = $admin->tenant_id;
        $settingExists = \App\Models\Setting::where('key', 'currency_after_amount')->where('tenant_id', $userTenantId)->exists();
        if (! $settingExists) {
            session(['tenant_id' => $userTenantId]);
            Artisan::call('db:seed', ['--class' => 'AddCurrencyIconSetting', '--force' => true]);
        }
    }
});

Route::get('/upgrade-to-v4-3-0', function () {
    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_08_23_115102_add_is_approved_column_in_payments_table.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_08_24_122922_add_auto_approved_setting_field.php',
    ]);

    Artisan::call('migrate', [
        '--force' => true,
        '--path' => 'database/migrations/2022_08_25_152334_add_currency_position_key_in_super_admin_setting_table.php',
    ]);

    $admins = User::whereHas('roles', function ($query) {
        $query->where('name', Role::ROLE_ADMIN);
    })->get();

    /** @var User $admin */
    foreach ($admins as $admin) {
        $userTenantId = $admin->tenant_id;
        $settingExists = \App\Models\Setting::where('key', 'payment_auto_approved')->where('tenant_id',
            $userTenantId)->exists();
        if (! $settingExists) {
            /** @var SettingRepository $settingRepo */
            $settingRepo = App::make(SettingRepository::class);
            $settingRepo->addPaymentAutoApproveSetting($userTenantId);
        }
    }
});

Route::get('/upgrade/database', function () {
    if (config('app.enable_upgrade_route')) {
        Artisan::call('migrate', [
            '--force' => true,
        ]);
    }

    return redirect(\route('login'));
});
