<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UserTest extends TestCase
{
    #[TestDox('Test getters and setters of User entity')]
    #[Test]
    public function test_user_entity_getters_and_setters(): void
    {
        $user = new User(
            id: '550e8400-e29b-41d4-a716-446655440000',
            name: 'Name',
            lastname: 'Lastname',
            email: 'Email',
            password: 'Password',
            phone: 'Phone',
            isActive: true,
            language: 'en',
            createdAt: '2024-01-01 00:00:00',
            updatedAt: '2024-01-01 00:00:00'
        );

        // Verificar getters iniciales
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $user->getId());
        $this->assertEquals('Name', $user->getName());
        $this->assertEquals('Lastname', $user->getLastname());
        $this->assertEquals('Email', $user->getEmail());
        $this->assertEquals('Password', $user->getPassword());
        $this->assertEquals('Phone', $user->getPhone());
        $this->assertTrue($user->getIsActive());
        $this->assertEquals('en', $user->getLanguage());
        $this->assertEquals('2024-01-01 00:00:00', $user->getCreatedAt());
        $this->assertEquals('2024-01-01 00:00:00', $user->getUpdatedAt());

        // Probar setters
        $user->setName('UpdatedName');
        $user->setLastname('UpdatedLastname');
        $user->setEmail('UpdatedEmail');
        //        $user->setPassword('UpdatedPassword');
        $user->setPhone('UpdatedPhone');
        $user->setIsActive(false);
        $user->setLanguage('es');
        $user->setCreatedAt('2024-02-01 00:00:00');
        $user->setUpdatedAt('2024-02-01 00:00:00');

        $this->assertEquals('UpdatedName', $user->getName());
        $this->assertEquals('UpdatedLastname', $user->getLastname());
        $this->assertEquals('UpdatedEmail', $user->getEmail());
        //        $this->assertEquals('UpdatedPassword', $user->getPassword());
        $this->assertEquals('UpdatedPhone', $user->getPhone());
        $this->assertFalse($user->getIsActive());
        $this->assertEquals('es', $user->getLanguage());
        $this->assertEquals('2024-02-01 00:00:00', $user->getCreatedAt());
        $this->assertEquals('2024-02-01 00:00:00', $user->getUpdatedAt());
    }
}
