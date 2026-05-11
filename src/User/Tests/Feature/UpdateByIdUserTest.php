<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateByIdUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Update User by ID, verify structure and type')]
    #[Test]
    public function update_user_by_id_with_correct_data(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'name' => 'Updated Value 1',
            'lastname' => 'Updated Value 2',
            'email' => 'email@email.com',
            'phone' => 'Updated Value 5',
            'isActive' => false,
        ];

        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $token = Token::generateToken($user);

        $url = "/api/user/{$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'lastname',
                'email',
                'phone',
                'isActive',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.updated', $responseOriginal['message']);
        $this->assertInstanceOf(UserResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(User::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('users', [
            'name' => 'Updated Value 1',
            'lastname' => 'Updated Value 2',
            'email' => 'email@email.com',
            'phone' => 'Updated Value 5',
            'is_active' => false,
        ]);

    }

    #[TestDox('Update User by ID,with only one field')]
    #[Test]
    public function update_user_by_id_with_only_one_field(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'is_active' => false,
        ];

        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $token = Token::generateToken($user);

        $url = "/api/user/{$user->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,

        ]);
    }

    #[TestDox('Update User by ID,with only language')]
    #[Test]
    public function update_user_by_id_with_only_language(): void
    {
        $user = UserModel::factory()->create();
        $data = [
            'language' => 'es',
        ];

        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $token = Token::generateToken($user);

        $url = "/api/user/{$user->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'language' => 'es',
        ]);
    }

    #[TestDox('Update User with bad ID, expect 404')]
    #[Test]
    public function update_user_with_bad_id(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'is_active' => false,
        ];

        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $token = Token::generateToken($user);

        $url = '/api/user/999999';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(404);

        $this->assertequals('User with data 999999 not found.', $response->json()['message']);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'is_active' => 0,

        ]);
    }

    #[TestDox('Update User with duplicated email in other users (Not updated)')]
    #[Test]
    public function update_user_with_duplicated_email(): void
    {
        $user = UserModel::factory()->create();
        $user2 = UserModel::factory()->create();
        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $data = [
            'email' => $user2->email,
        ];

        $token = Token::generateToken($user);

        $url = "/api/user/{$user->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(422);

        $response->assertJsonStructure([
            'ok',
            'message',
            'data' => [
                'email',
            ],

        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,

        ]);

    }

    #[TestDox('Update other users role field_supervisor (Not updated)')]
    #[Test]
    public function can_not_update_other_user_with_role_field_supervisor(): void
    {
        $user = UserModel::factory()->create();
        $user2 = UserModel::factory()->create();
        $roleSuperAdmin = Role::findByName('field_supervisor', 'api');
        $user->assignRole($roleSuperAdmin);
        $data = [
            'name' => 'otro nombre',
        ];

        $token = Token::generateToken($user);

        $url = "/api/user/{$user2->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(403);

        $response->assertJsonStructure([
            'ok',
            'message',
            'data',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'name' => $user2->name,
        ]);

    }

    #[TestDox('Update any users role super_admin (Updated)')]
    #[Test]
    public function can_update_other_user_with_role_super_admin(): void
    {
        $user = UserModel::factory()->create();
        $user2 = UserModel::factory()->create();
        $roleSuperAdmin = Role::findByName('super_admin', 'api');
        $user->assignRole($roleSuperAdmin);
        $data = [
            'name' => 'otro nombre',
        ];

        $token = Token::generateToken($user);

        $url = "/api/user/{$user2->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'ok',
            'message',
            'data',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'name' => 'otro nombre',
        ]);

    }

    #[TestDox('Update self User by ID')]
    #[Test]
    public function update_self_user_by_id(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'isActive' => false,
        ];

        $token = $this->getToken('field_supervisor', $user);

        $url = "/api/user/{$user->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => 0,
        ]);
    }

    #[TestDox('can not Update other User by ID if not have permission')]
    #[Test]
    public function can_not_update_other_user_by_id(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'isActive' => false,
        ];

        $token = $this->getToken('field_supervisor');

        $url = "/api/user/{$user->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => 1,
        ]);
    }

    #[TestDox('can Update User all field if same email')]
    #[Test]
    public function can_update_user_all_field_if_same_email(): void
    {
        $user = UserModel::factory()->create();

        $data = [
            'isActive' => false,
            'email' => $user->email,
            'name' => 'otro nombre',
            'lastname' => 'otro apellido',
        ];

        $token = $this->getToken('super_admin');

        $url = "/api/user/{$user->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => 0,
            'name' => 'otro nombre',
            'lastname' => 'otro apellido',
            'email' => $user->email,
        ]);
    }
}
