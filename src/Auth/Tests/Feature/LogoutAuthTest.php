<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class LogoutAuthTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Logout auth with correct data (OK)')]
    #[Test]
    public function logout_with_correct_data(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        $token = $user->createToken(config('token.token_name'))->accessToken;
        $url = '/api/auth/logout';
        $this->withHeaders(['Authorization' => "Bearer {$token}"])->getJson($url)
            ->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.logout',
                'data' => true,
            ]);

    }

    #[TestDox('Logout auth verify response structure')]
    #[Test]
    public function logout_verify_response_structure(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        $token = $user->createToken(config('token.token_name'))->accessToken;
        $url = '/api/auth/logout';
        $this->withHeaders(['Authorization' => "Bearer {$token}"])->getJson($url)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data',
            ]);

    }

    #[TestDox('Logout without token (UNAUTHENTICATED)')]
    #[Test]
    public function logout_without_token(): void
    {
        UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        $url = '/api/auth/logout';
        $response = $this->getJson($url)
            ->assertStatus(401);

        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data',
        ]);

    }

    #[TestDox('Logout with bad token (UNAUTHENTICATED)')]
    #[Test]
    public function logout_with_bad_token(): void
    {
        UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        $url = '/api/auth/logout';
        $this->getJson($url, ['Authorization' => 'Bearer bad_token'])
            ->assertStatus(401);

    }
}
