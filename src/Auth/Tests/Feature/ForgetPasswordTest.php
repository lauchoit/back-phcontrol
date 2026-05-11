<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ForgetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Send email password recover by email (OK)')]
    #[Test]
    public function send_email_password_recover_by_email(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'email@recover.com',
        ]);

        $response = $this->getJson("/api/auth/forget-password/{$user->email}")
            ->assertStatus(201);
        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                'email',
                'urlRecover',
            ],
        ]);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    #[TestDox('Send email password recover bay phone (OK)')]
    #[Test]
    public function send_email_password_recover_by_phone(): void
    {
        //        config(['mail.default' => 'smtp']);

        $user = UserModel::factory()->create([
            'email' => 'Email@recover.com',
        ]);

        $response = $this->getJson("/api/auth/forget-password/{$user->phone}")
            ->assertStatus(201);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    #[TestDox('Send email password recover user not exists (NOT FOUND)')]
    #[Test]
    public function send_email_password_recover_user_not_exist(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'email@recover.com',
        ]);

        $response = $this->getJson('/api/auth/forget-password/aaaaaaaaaaaa')
            ->assertStatus(404);
        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.not_found',
            'data' => null,
        ]);
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }
}
