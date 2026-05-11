<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Resources\AuthResource;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class MeAuthTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find Auth user, verify structure and type')]
    #[Test]
    public function find_auth_user_and_verify_structure_and_type(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $permission = PermissionModel::create(['name' => 'test.me.permission', 'guard_name' => 'api']);
        $user->givePermissionTo($permission->id);

        $url = '/api/auth/me';
        $token = Token::generateToken($user);
        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])->getJson($url)
            ->assertStatus(200);

        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'lastname',
                    'email',
                    'phone',
                    'isActive',
                    'language',
                    'createdAt',
                    'updatedAt',
                ],
                'token',
                'permissions',
            ],
        ]);
        $this->assertContains($permission->name, $response->json('data.permissions'));

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(AuthResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Auth::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find auth user without token (Unauthorized)')]
    #[Test]
    public function find_auth_user_without_token(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        $url = '/api/auth/me';
        $this->getJson($url)
            ->assertStatus(401);

    }

    #[TestDox('Find auth user with bad token (Unauthorized)')]
    #[Test]
    public function find_auth_user_with_bad_token(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);

        auth()->login($user);

        $token = Token::generateToken($user);

        $url = '/api/auth/me';
        $this->withHeaders(['Authorization' => "Bearer {$token}xx"])->getJson($url)
            ->assertStatus(401);

    }
}
