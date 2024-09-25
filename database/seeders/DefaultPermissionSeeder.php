<?php

namespace Database\Seeders;

use App\Models\Role as CustomRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'manage_user',
                'display_name' => 'Manage User',
            ],
            [
                'name' => 'manage_roles',
                'display_name' => 'Manage Roles',
            ],
        ];
        foreach ($permissions as $permission) {
            // Permission::create($permission);
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        /** @var Role $adminRole */
        $adminRole = Role::whereName(CustomRole::ROLE_ADMIN)->first();

        $allPermission = Permission::toBase()->pluck('name', 'id')->toArray();
        $adminRole->givePermissionTo($allPermission);
    }
}
