<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\PermissionResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateByIdPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Update Permission by ID, verify structure and type')]
    #[Test]
    public function update_permission_by_id_with_correct_data(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $data = [
            'name' => 'system_updated',
            'guardName' => 'web',
        ];

        $url = "/api/access-control/permission/{$permission->id}";

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
        $this->assertInstanceOf(PermissionResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Permission::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('permissions', [
            'name' => 'system_updated',
            'guard_name' => 'web',
        ]);
    }

    #[TestDox('Update Permission by ID,with only one field')]
    #[Test]
    public function update_permission_by_id_with_only_one_field(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $data = [
            'name' => 'system_updated',
        ];

        $url = "/api/access-control/permission/{$permission->id}";

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
        $this->assertInstanceOf(PermissionResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Permission::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('permissions', [
            'name' => 'system_updated',
            'guard_name' => 'api',
        ]);
    }

    #[TestDox('Update Permission with bad ID, expect 404')]
    #[Test]
    public function update_permission_with_bad_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');

        $data = [
            'name' => 'Updated Value 2',
        ];

        $url = '/api/access-control/permission/999999';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(404);

        $this->assertequals('Permission with ID 999999 not found.', $response->json()['message']);

        $this->assertDatabaseMissing('roles', [
            'id' => $permission->id,
            'name' => 'Updated Value 2',

        ]);
    }

    #[TestDox('Cannot Update Permission if not Super Admin')]
    #[Test]
    public function can_not_update_permission_if_not_super_admin(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystemAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $data = [
            'name' => 'system_updated',
        ];

        $url = "/api/access-control/permission/{$permission->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(403);

        $response->assertJson([
            'ok' => false,
            'message' => 'user.unauthorized',
            'data' => null,
        ]);

    }
}
