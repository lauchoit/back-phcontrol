<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\RoleResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateByIdRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Update Role by ID, verify structure and type')]
    #[Test]
    public function update_role_by_id_with_correct_data(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $role = RoleModel::findByName('system_admin', 'api');
        $data = [
            'name' => 'system_updated',
            'guardName' => 'web',
        ];

        $url = "/api/access-control/role/{$role->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'guardName',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.updated', $responseOriginal['message']);
        $this->assertInstanceOf(RoleResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Role::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('roles', [
            'name' => 'system_updated',
            'guard_name' => 'web',
        ]);
    }

    #[TestDox('Update Role by ID,with only one field')]
    #[Test]
    public function update_role_by_id_with_only_one_field(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $role = RoleModel::findByName('system_admin', 'api');
        $data = [
            'name' => 'system_updated',
        ];

        $url = "/api/access-control/role/{$role->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'guardName',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.updated', $responseOriginal['message']);
        $this->assertInstanceOf(RoleResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Role::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('roles', [
            'name' => 'system_updated',
            'guard_name' => 'api',
        ]);

    }

    #[TestDox('Update Role with bad ID, expect 404')]
    #[Test]
    public function update_role_with_bad_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $role = RoleModel::findByName('system_admin', 'api');

        $data = [
            'name' => 'Updated Value 2',
        ];

        $url = '/api/access-control/role/999999';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(404);

        $this->assertequals('Role with ID 999999 not found.', $response->json()['message']);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
            'name' => 'Updated Value 2',

        ]);
    }

    #[TestDox('can not update role when user logged is not super admin (UNAUTHORIZED)')]
    #[Test]
    public function can_not_update_role_with_user_logged_is_authorized(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('field_supervisor', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystemAdmin);

        $role = RoleModel::findByName('field_supervisor', 'api');

        $data = [
            'name' => 'Updated Value 2',
        ];

        $url = "/api/access-control/role/$role->id";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(403);

        $response->assertJson([
            'ok' => false,
            'message' => 'user.unauthorized',
            'data' => null,
        ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
            'name' => 'Updated Value 2',

        ]);
    }

    #[TestDox('Update Role by ID, when is user authorized')]
    #[Test]
    public function update_role_by_id_when_is_user_authorized(): void
    {
        $token = $this->getToken('system_admin');

        $role = RoleModel::findByName('field_supervisor', 'api');
        $data = [
            'name' => 'system_updated',
            'guardName' => 'web',
        ];

        $url = "/api/access-control/role/{$role->id}";

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);
    }
}
