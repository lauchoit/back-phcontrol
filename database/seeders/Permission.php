<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'user',
            'roles',
            'permissions',
            'country',
            'email-template',
            'product'
        ];

        $permissionsTypes = [
            'find.all',
            'find.own',
            'find.by.id',
            'create',
            'update.by.id',
            'delete.by.id',
        ];

        $permissions = [];
        foreach ($types as $type) {
            foreach ($permissionsTypes as $permissionType) {
                $permissions[] = "{$type}.{$permissionType}";
            }
        }

        foreach ($permissions as $permission) {
            PermissionModel::findOrCreate($permission, 'api');
        }

        $extras = [
            'user.add.permission',
            'user.sync.roles',
            'roles.sync.permission',
        ];

        foreach ($extras as $permission) {
            PermissionModel::findOrCreate($permission, 'api');
        }
    }
}
