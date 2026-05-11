<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            Roles::class,
            Permission::class,
            PassportSeeder::class,
            EmailTemplateSeeder::class,
        ]);

        $roleSystemAdmin = Role::findByName('system_admin', 'api');
        $permission = PermissionModel::all()->pluck('name');
        $roleSystemAdmin->syncPermissions($permission);
    }
}
