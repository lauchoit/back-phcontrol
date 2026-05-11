<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\RoleResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindByIdRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('find by id role with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_by_id_role_with_user_guest(): void
    {
        $role = RoleModel::first();
        $url = "/api/access-control/role/{$role->id}";
        $response = $this->getJson($url)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find by id role with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_by_id_role_with_user_unauthorized(): void
    {
        $role = RoleModel::first();
        $url = "/api/access-control/role/{$role->id}";
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson($url);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find by id role when user is authorized (OK)')]
    #[Test]
    public function can_find_id_role_if_is_authorized(): void
    {
        $role = RoleModel::first();
        $url = "/api/access-control/role/{$role->id}";
        $token = $this->getToken('system_admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson($url);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'ok' => true,
                'message' => 'success.search',
            ]);
    }

    #[TestDox('Can not find Role by ID, verify structure and type')]
    #[Test]
    public function can_not_find_role_by_id_guest_user(): void
    {
        $role = RoleModel::create(['name' => 'new_role', 'guard_name' => 'api']);
        $url = "/api/access-control/role/{$role->id}";

        $response = $this->getJson($url)
            ->assertStatus(401);

        $response->assertJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Find Role by ID, verify structure and type')]
    #[Test]
    public function find_role_by_id_and_verify_structure_and_type(): void
    {
        $role = RoleModel::create(['name' => 'new_role', 'guard_name' => 'api']);
        $permissions = Permission::limit(3)->pluck('id')->toArray();
        $role->permissions()->sync($permissions);

        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $url = "/api/access-control/role/{$role->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(200);

        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                'id',
                'name',
                'guardName',
                'usersCount',
                'permissions' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
                'createdAt',
                'updatedAt',
            ],
        ]);
        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(RoleResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Role::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find Role by invalid ID, expect 404 error')]
    #[Test]
    public function find_role_with_invalid_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);
        $url = '/api/access-control/role/999999'; // ID que no existe

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(404);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('error.not_found', $responseOriginal['message']);
    }
}
