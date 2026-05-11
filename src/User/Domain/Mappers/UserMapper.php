<?php

namespace Lauchoit\LaravelHexMod\User\Domain\Mappers;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

class UserMapper
{
    /**
     * Maps the fields from the UserModel to the User entity.
     */
    public static function toDomain(array $user): User
    {
        return new User(
            id: $user['id'],
            name: $user['name'],
            lastname: $user['lastname'],
            email: $user['email'],
            password: $user['password'] ?? '*********',
            phone: $user['phone'],
            isActive: $user['is_active'],
            language: $user['language'],
            createdAt: $user['created_at'],
            updatedAt: $user['updated_at'],
        );
    }

    /**
     * Converts a array of UserModels models to an array of User.
     *
     * @return User[]
     */
    public static function toDomainArray(array $userModels): array
    {
        return array_map(fn (array $userModel) => self::toDomain($userModel), $userModels);
    }

    /**
     * Maps raw data to the UserModel for persistence.
     */
    public static function toPersistence(array $data, ?array $userModel = null): array
    {
        $model = $userModel ?? [];

        $model['name'] = $data['name'] ?? $model['name'];
        $model['lastname'] = $data['lastname'] ?? $model['lastname'];
        $model['email'] = $data['email'] ?? $model['email'];
        $model['phone'] = $data['phone'] ?? $model['phone'];

        if (isset($data['language']) || $userModel) {
            $model['language'] = $data['language'] ?? $model['language'];
        }

        if (isset($data['password'])) {
            $model['password'] = $data['password'];
        }

        if (isset($data['isActive']) || $userModel) {
            $model['is_active'] = $data['isActive'] ?? $model['is_active'];
        }

        return $model;
    }
}
