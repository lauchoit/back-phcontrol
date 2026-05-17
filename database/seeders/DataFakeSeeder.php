<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class DataFakeSeeder extends Seeder
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

        ProductModel::factory()->count(30)->create();
    }
}
