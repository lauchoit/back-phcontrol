<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;

class RoleMapper
{
    /**
     * Maps the fields from the RoleModel to the Role entity.
     */
    public static function toDomain(array $role): Role
    {
        //        dd($role);
        return new Role(
            id: $role['id'],
            name: $role['name'],
            guardName: $role['guard_name'],
            createdAt: $role['created_at'],
            updatedAt: $role['updated_at'],
            permissions: array_map(
                fn (array $permission) => [
                    'id' => $permission['id'],
                    'name' => $permission['name'],
                ],
                $role['permissions'] ?? []
            ),
            usersCount: (int) ($role['users_count'] ?? 0),
        );
    }

    /**
     * Converts a array of RoleModels models to an array of Role.
     *
     * @return Role[]
     */
    public static function toDomainArray(array $roleModels): array
    {
        return array_map(fn (array $roleModel) => self::toDomain($roleModel), $roleModels);
    }

    /**
     * Maps raw data to the RoleModel for persistence.
     */
    public static function toPersistence(array $data, ?array $roleModel = null): array
    {
        $model = $roleModel ?? [];

        $model['name'] = $data['name'] ?? $model['name'];
        $model['guard_name'] = $data['guardName'] ?? $model['guard_name'];

        return $model;
    }
}
