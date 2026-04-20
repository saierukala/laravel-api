<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['users', 'roles', 'gallery.folders', 'gallery.images'] as $resource) {
            foreach (['create', 'view', 'edit', 'delete'] as $action) {
                Permission::findOrCreate("{$resource}.{$action}", 'web');
            }
        }
    }
}
