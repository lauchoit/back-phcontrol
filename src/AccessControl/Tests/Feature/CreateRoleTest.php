<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
// use Laravel\Sanctum\Sanctum;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\RoleResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Create role from guest user (UNAUTHENTICATED)')]
    #[Test]
    public function create_role_guest_user(): void
    {
        $data = [
            'name' => 'new_role',
            'guardName' => 'api',
        ];

        $this->postJson('/api/access-control/role', $data)
            ->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('can not create role is different to super_user authenticated')]
    #[Test]
    public function can__not_create_role_is_different_to_supe_user_authenticated(): void
    {

        $userSystem = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystem->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystem);

        $data = ['name' => 'new_role', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/role', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Create role with correct data (OK)')]
    #[Test]
    public function create_role_check_entity(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);
        $data = [
            'name' => 'new_role',
            'guardName' => 'api',
        ];

        $token = Token::generateToken($userSuperAdmin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->postJson('/api/access-control/role', $data)
            ->assertStatus(201);

        $this->assertInstanceOf(RoleResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(Role::class, $response->getOriginalContent()['data']->resource);
        $this->assertDatabaseHas('roles', [
            'name' => 'new_role', 'guard_name' => 'api',
        ]);
    }

    #[TestDox('Create role check response structure')]
    #[Test]
    public function create_role_check_structure(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);
        $token = Token::generateToken($userSuperAdmin);
        $data = [
            'name' => 'new_role',
            'guardName' => 'api',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/role', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'guardName',
                    'createdAt',
                    'usersCount',
                    'updatedAt',
                ],
            ]);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('roles', [
            'name' => 'new_role',
            'guard_name' => 'api',
        ]);
    }

    #[TestDox('Create role with missing fields (NO registered)')]
    #[Test]
    public function create_role_with_missing_field(): void
    {
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $data = [
            'name' => '',
            'guard_name' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/role', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'name' => [
                0 => 'The name field is required.',
            ],
            'guardName' => [
                0 => 'The guard name field is required.',
            ],
        ]);
    }

    #[TestDox('Create role with duplicated role name (NO registered)')]
    #[Test]
    public function create_role_with_duplicate_role_name(): void
    {

        RoleModel::create(['name' => 'new_role', 'guard_name' => 'api']);
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $data = ['name' => 'new_role', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/role', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'name' => [
                0 => 'The name has already been taken.',
            ],
        ]);
    }

    #[TestDox('can create role is super_user authenticated')]
    #[Test]
    public function can_create_role_is_supe_user_authenticated(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);
        $data = ['name' => 'new_role', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/role', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'guardName',
                    'createdAt',
                    'usersCount',
                    'updatedAt',
                ],
            ]);
    }
}
