<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Application\UserCases;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\User\Application\UseCases\SyncRolesToUserUseCase;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SyncRolesToUserUseCaseTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    #[TestDox('can not sync roles to user when have bad user id')]
    public function can_not_sync_roles_to_user_when_have_bad_user_id(): void
    {
        $data = [
            'userId' => 9999,
            'roleIds' => [1, 2],
        ];
        try {

            $syncRolesToUser = app(SyncRolesToUserUseCase::class);
            $syncRolesToUser->execute($data['userId'], $data['roleIds']);
            $this->expectException(UserNotFoundException::class);
        } catch (\Exception $e) {
            $this->assertInstanceOf(UserNotFoundException::class, $e);
        }
    }
}
