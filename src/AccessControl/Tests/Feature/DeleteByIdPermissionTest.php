<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class DeleteByIdPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Delete a Permission by ID')]
    #[Test]
    public function delete_permission_by_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');

        $url = "/api/access-control/permission/{$permission->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete($url)
            ->assertStatus(200);

        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    #[TestDox('Delete a Permission with bad ID')]
    #[Test]
    public function delete_permission_with_bad_id()
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $url = '/api/access-control/permission/999999';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete($url);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Permission with ID 999999 not found.',
        ]);
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'user.find.own', 'guard_name' => 'api']);
    }

    #[TestDox('Cannot delete a Permission with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_delete_a_permission_with_user_guest(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $url = "/api/access-control/permission/{$permission->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}A",
            'Content-Type' => 'application/json',
        ])->deleteJson($url);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'user.unauthenticated',
        ]);
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'user.find.own', 'guard_name' => 'api']);
    }

    #[TestDox('Cannot delete a Permission with unauthorized user (UNAUTHORIZED)')]
    #[Test]
    public function can_not_delete_a_permission_with_unauthorized(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystemAdmin);

        $permission = PermissionModel::findByName('user.find.own', 'api');
        $url = "/api/access-control/permission/{$permission->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->deleteJson($url);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'user.unauthorized',
        ]);
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'user.find.own', 'guard_name' => 'api']);
    }
}
