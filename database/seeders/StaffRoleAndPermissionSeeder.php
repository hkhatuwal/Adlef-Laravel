<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StaffRoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'view asset transfers',
            'verify transfer payment',
            'view otc trades',
            'process trade',
            'view users',
            'verify user'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create staff role
        $staffRole = Role::create(['name' => 'staff']);

        // Assign permissions to staff role
        $staffRole->givePermissionTo($permissions);
    }
} 