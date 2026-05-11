<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\ClientRepository;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $clientRepo = new ClientRepository;
        $clientRepo->createPersonalAccessGrantClient(
            'test-personal-access-client',
        );
    }

    protected function getToken($role, ?UserModel $userSystem = null): string
    {
        if ($userSystem === null) {
            $userSystem = UserModel::factory()->create();
        }

        $roleSelected = RoleModel::findByName($role, 'api');
        $userSystem->assignRole($roleSelected);

        return Token::generateToken($userSystem);
    }
}
