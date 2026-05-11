<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;

class PermissionMapper
{
    /**
     * Maps the fields from the PermissionModel to the Permission entity.
     */
    public static function toDomain(array $permission): Permission
    {
        return new Permission(
            id: $permission['id'],
            name: $permission['name'],
            guardName: $permission['guard_name'],
            createdAt: $permission['created_at'],
            updatedAt: $permission['updated_at'],
        );
    }

    /**
     * Converts a array of PermissionModels models to an array of Permission.
     *
     * @return Permission[]
     */
    public static function toDomainArray(array $permissionModels): array
    {
        return array_map(fn (array $permissionModel) => self::toDomain($permissionModel), $permissionModels);
    }

    /**
     * Maps raw data to the PermissionModel for persistence.
     */
    public static function toPersistence(array $data, ?array $permissionModel = null): array
    {
        $model = $permissionModel ?? [];

        $model['name'] = $data['name'] ?? $model['name'];
        $model['guard_name'] = $data['guardName'] ?? $model['guard_name'];

        return $model;
    }
}
