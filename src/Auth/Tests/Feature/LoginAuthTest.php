<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Resources\AuthResource;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class LoginAuthTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Login auth with correct data (OK)')]
    #[Test]
    public function login_auth_check_entity(): void
    {
        $dataUser = [
            'email' => 'email@email.com',
            'password' => bcrypt('password'),
        ];

        UserModel::factory()->create($dataUser);

        $data = [
            'email' => $dataUser['email'],
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $data)
            ->assertStatus(200)
            ->assertJsonFragment([
                'ok' => true,
                'message' => 'success.login',
            ]);

        $this->assertInstanceOf(AuthResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(Auth::class, $response->getOriginalContent()['data']->resource);
    }

    #[TestDox('Login auth check response structure')]
    #[Test]
    public function login_auth_check_structure(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $permission = PermissionModel::create(['name' => 'test.login.permission', 'guard_name' => 'api']);
        $user->givePermissionTo($permission->id);

        $data = [
            'email' => $user->email,
            'password' => 'Password',
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(200)
            ->assertExactJsonStructure([
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
        $response->assertJsonFragment([
            'email' => $user->email,
            'name' => $user->name,
            'lastname' => $user->lastname,
        ]);
        $this->assertContains($permission->name, $response->json('data.permissions'));
    }

    #[TestDox('Login auth with missing fields (NO Login)')]
    #[Test]
    public function login_auth_with_missing_field(): void
    {
        //        $this->withoutExceptionHandling();
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $data = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'email' => [
                0 => 'The email field is required.',
            ],
            'password' => [
                0 => 'The password field is required.',
            ],
        ]);
    }

    #[TestDox('Login auth with invalid email (NO Login)')]
    #[Test]
    public function login_auth_with_invalid_email(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $data = [
            'email' => 'email',
            'password' => 'Password',
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'email' => [
                0 => 'The email field must be a valid email address.',
            ],
        ]);
    }

    #[TestDox('Login auth with bad password (NO Login)')]
    #[Test]
    public function login_auth_with_bad_password(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $data = [
            'email' => $user->email,
            'password' => 'bad_password',
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);

        $response->assertJson([
            'ok' => false,
            'message' => 'Invalid credentials provided.',
            'data' => null,
        ]);
    }

    #[TestDox('Login auth with bad email (NO Login)')]
    #[Test]
    public function login_auth_with_bad_email(): void
    {
        $user = UserModel::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('Password'),
        ]);
        $data = [
            'email' => 'other@email.com',
            'password' => 'Password',
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);

        $response->assertJson([
            'ok' => false,
            'message' => 'Invalid credentials provided.',
            'data' => null,
        ]);
    }
}
