<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $adminPermissions = [
            'manage-users',
            'manage-leads',
            'manage-lead-sources',
            'manage-categories',
            'manage-countries',
            'manage-subscription-plans',
            'manage-payments',
            'manage-exports',
            'manage-settings',
            'manage-queue-jobs',
            'view-reports',
            'view-activity-log',
        ];

        $userPermissions = [
            'access-dashboard',
            'manage-profile',
            'search-leads',
            'filter-leads',
            'bookmark-leads',
            'manage-lists',
            'export-leads',
            'receive-notifications',
        ];

        foreach (array_merge($adminPermissions, $userPermissions) as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($adminPermissions);

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions($userPermissions);
    }
}
