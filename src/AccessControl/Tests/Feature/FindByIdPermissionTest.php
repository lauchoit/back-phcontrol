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

class FindByIdPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not find Permission by ID (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_find_permission_by_id_guest_user(): void
    {
        $role = RoleModel::create(['name' => 'new_role', 'guard_name' => 'api']);
        $url = "/api/access-control/permission/{$role->id}";

        $response = $this->getJson($url)
            ->assertStatus(401);

        $response->assertJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find Permission by ID, whe user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_by_id_when_user_unauthorized(): void
    {
        $permission = PermissionModel::create(['name' => 'new.permission', 'guard_name' => 'api']);
        $token = $this->getToken('field_supervisor');

        $url = "/api/access-control/permission/{$permission->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(403);
    }

    #[TestDox('Find Permission by ID, whe user authorized (OK)')]
    #[Test]
    public function find_by_id_when_user_authorized(): void
    {
        $permission = PermissionModel::create(['name' => 'new.permission', 'guard_name' => 'api']);
        $token = $this->getToken('system_admin');

        $url = "/api/access-control/permission/{$permission->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(200);
    }

    #[TestDox('Find Permission by ID, verify structure and type')]
    #[Test]
    public function find_by_id_permissions_and_verify_structure_and_type(): void
    {
        $permission = PermissionModel::create(['name' => 'new.permission', 'guard_name' => 'api']);
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $url = "/api/access-control/permission/{$permission->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
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
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(PermissionResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Permission::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find Permission by invalid ID, expect 404 error')]
    #[Test]
    public function find_permission_with_invalid_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);
        $url = '/api/access-control/permission/999999';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(404);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('error.not_found', $responseOriginal['message']);
    }
}
