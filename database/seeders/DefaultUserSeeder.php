<?php

namespace Database\Seeders;

use App\Models\MultiTenant;
use App\Models\Role as CustomRole;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', CustomRole::ROLE_SUPER_ADMIN);
        })->exists();

        if (!$user) {
            $superAdminTenant = MultiTenant::create(['tenant_username' => 'superadmin']);
            $superAdmin = [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@infy-invoices.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456'),
                'tenant_id' => $superAdminTenant->id,
            ];

            $user = User::create($superAdmin);
            $user->assignRole(CustomRole::ROLE_SUPER_ADMIN);
            // Artisan::call('db:seed', ['--class' => 'SettingsTableSeeder', '--force' => true]);
            // Artisan::call('db:seed', ['--class' => 'SettingTableSeederFields', '--force' => true]);
            // Artisan::call('db:seed', ['--class' => 'SettingTablePaymentGatewayFieldSeeder', '--force' => true]);
            // Artisan::call('db:seed', ['--class' => 'SettingFavIconFieldSeeder', '--force' => true]);
            // Artisan::call('db:seed', ['--class' => 'InvoiceSettingTableSeeder', '--force' => true]);
            // Artisan::call('db:seed', ['--class' => 'InvoiceSettingTemplateSeeder', '--force' => true]);

            $adminTenant = MultiTenant::create(['tenant_username' => 'admin']);
            $admin = [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@infy-invoices.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456'),
                'tenant_id' => $adminTenant->id,
            ];

            $user = User::create($admin);
            $user->assignRole(CustomRole::ROLE_ADMIN);

            $subscriptionPlan = SubscriptionPlan::where('is_default', 1)->first();

            $trialDays = $subscriptionPlan->trial_days;
            $subscription = [
                'user_id' => $user->id,
                'subscription_plan_id' => $subscriptionPlan->id,
                'plan_amount' => $subscriptionPlan->price,
                'plan_frequency' => $subscriptionPlan->frequency,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays($trialDays),
                'trial_ends_at' => Carbon::now()->addDays($trialDays),
                'status' => Subscription::ACTIVE,
            ];
            Subscription::create($subscription);

            session(['tenant_id' => $adminTenant->id]);
            Artisan::call('db:seed', ['--class' => 'AddClientUserSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SettingsTableSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SettingTableSeederFields', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SettingTablePaymentGatewayFieldSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SettingFavIconFieldSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'InvoiceSettingTableSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'InvoiceSettingTemplateSeeder', '--force' => true]);


            /** @var UserRepository $userRepo */
            $userRepo = App::make(UserRepository::class);
            $userRepo->addSettingRecord('currency_after_amount', $adminTenant->id, '1');
            $userRepo->addSettingRecord('payment_auto_approved', $adminTenant->id, '1');
            $userRepo->addSettingRecord('invoice_no_prefix', $adminTenant->id);
            $userRepo->addSettingRecord('invoice_no_suffix', $adminTenant->id);
            $userRepo->addSettingRecord('country_code', $adminTenant->id, 'in');
        }
    }
}
