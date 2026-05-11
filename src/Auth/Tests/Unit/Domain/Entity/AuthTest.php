<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class AuthTest extends TestCase
{
    #[TestDox('Test getters and setters of Auth entity')]
    #[Test]
    public function test_auth_entity_getters_and_setters(): void
    {
        $auth = new Auth(user: new User(
            id: 1,
            name: 'Name',
            lastname: 'Lastname',
            email: 'Email',
            password: 'Password',
            phone: '5555555555',
            isActive: true,
            language: 'en',
            createdAt: '2024-01-01 00:00:00',
            updatedAt: '2024-01-01 00:00:00'
        ),
            token: 'token',
            permissions: [
                new Permission(
                    id: 1,
                    name: 'permission.name',
                    guardName: 'api',
                    createdAt: '2024-01-01 00:00:00',
                    updatedAt: '2024-01-01 00:00:00'
                ),
            ],
        );
        $this->assertInstanceOf(User::class, $auth->getUser());
        $this->assertEquals('token', $auth->getToken());
        $this->assertContainsOnlyInstancesOf(Permission::class, $auth->getPermissions());
    }
}
