<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Roles::class,
            Permission::class,
            PassportSeeder::class,
            EmailTemplateSeeder::class,
        ]);

        $superAdmin = User::factory()->create([
            'name' => 'admin',
            'lastname' => 'sir',
            'email' => 'ejemplo@ejemplo.com',
            'password' => bcrypt('12345678'),
        ]);

        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $superAdmin->assignRole($roleSuperAdmin);

        $userSystemAdmin = User::factory()->create([
            'name' => 'system',
            'lastname' => 'admin',
            'email' => 'system@ejemplo.com',
            'password' => bcrypt('12345678'),
        ]);

        $roleSystemAdmin = Role::findByName('system_admin', 'api');
        $permissions = PermissionModel::all()->pluck('name');
        $roleSystemAdmin->syncPermissions($permissions);

        $userSystemAdmin->assignRole($roleSystemAdmin);
    }
}
