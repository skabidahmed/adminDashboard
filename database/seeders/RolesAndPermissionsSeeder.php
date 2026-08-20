<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions per Spatie package specifications
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Declare and Populate System Capabilities Matrix
        $permissions = [
            // User Resource Privileges
            'view any users',
            'view users',
            'create users',
            'update users',
            'delete users',
            'force delete users',

            // Content Resource Privileges
            'view any post',
            'view post',
            'create post',
            'update post',
            'delete post',

            // Operational Access Administration Privileges
            'view any roles',
            'view roles',
            'create roles',
            'update roles',
            'delete roles',
            
            'view any permissions',
            'view permissions',
            'create permissions',
            'update permissions',
            'delete permissions'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Establish Base Group Roles & Dynamically Map Group Privileges
        
        // Writer Configuration
        $writerRole = Role::findOrCreate('writer', 'web');
        $writerRole->givePermissionTo([
            'view any post',
            'view post',
            'create post',
            'update post'
        ]);

        // Moderator Configuration
        $moderatorRole = Role::findOrCreate('moderator', 'web');
        $moderatorRole->givePermissionTo([
            'view any users',
            'view users',
            'create users',
            'update users',
            'delete users',
            'view any post',
            'view post',
            'create post',
            'update post',
            'delete post',
            'view roles' // Access list visibility only, execution barred
        ]);

        // Standard Default Base Tier Role
        $userRole = Role::findOrCreate('user', 'web');
        $userRole->givePermissionTo([
            'view any post',
            'view post'
        ]);

        // Super Admin Role Group Interface Container
        // (Note: The AppServiceProvider global intercept block implicitly processes global permissions bypass checks)
        Role::findOrCreate('super_admin', 'web');
    }
}
