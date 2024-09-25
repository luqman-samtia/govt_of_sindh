<?php

namespace Database\Seeders;

use App\Models\MultiTenant;
use App\Models\Role as CustomRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddClientUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::whereEmail('admin@infy-invoices.com')->first();
        $tenant = MultiTenant::create(['tenant_username' => 'client']);
        $adminUserTenantID = session('tenant_id', null);
        $client = [
            'first_name' => 'Client',
            'last_name' => 'Invoice',
            'email' => 'client@infy-invoices.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('123456'),
            'tenant_id' => $tenant->id,
        ];

        $client = User::create($client);
        $client->client()->create([
            'user_id' => $adminUser->id,
            'postal_code' => '395010',
            'tenant_id' => $adminUserTenantID
        ]);
        $client->assignRole(CustomRole::ROLE_CLIENT);
    }
}
