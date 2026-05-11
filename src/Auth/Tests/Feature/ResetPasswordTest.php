<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Reset password with valid token (OK)')]
    #[Test]
    public function reset_password_with_valid_token(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'reset-password-user@example.com',
            'password' => Hash::make('old-password'),
        ]);
        $token = Password::broker()->createToken($user);

        $this->postJson("/api/auth/reset-password/{$token}", [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.updated',
                'data' => true,
            ]);

        $user->refresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    #[TestDox('Reset password with invalid token (BAD REQUEST)')]
    #[Test]
    public function reset_password_with_invalid_token(): void
    {
        $this->postJson('/api/auth/reset-password/invalid-token', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertStatus(400)
            ->assertExactJson([
                'ok' => false,
                'message' => 'password.reset.invalid_token',
                'data' => null,
            ]);
    }

    #[TestDox('Reset password requires confirmation (VALIDATION ERROR)')]
    #[Test]
    public function reset_password_requires_confirmation(): void
    {
        $user = UserModel::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->postJson("/api/auth/reset-password/{$token}", [
            'password' => 'new-password',
            'password_confirmation' => 'different-password',
        ])->assertStatus(422)
            ->assertJsonFragment([
                'password' => ['The password field confirmation does not match.'],
            ]);
    }
}
