<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Application\UserCases;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\User\Application\UseCases\FindByEmailPhoneUserUseCase;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindByEmailPhoneUserUseCaseTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find user by email or phone (OK)')]
    #[Test]
    public function find_user_by_email_or_phone(): void
    {
        UserModel::factory(3)->create();
        $user = UserModel::factory()->create([
            'email' => 'concto@lauchoit.com',
        ]);

        $findUserByEmailPhone = app(FindByEmailPhoneUserUseCase::class);
        $result = $findUserByEmailPhone->execute($user->email);
        $this->assertInstanceOf(User::class, $result);
    }
}
