<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class DeleteByIdRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Delete a Role by ID')]
    #[Test]
    public function delete_role_by_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $role = RoleModel::findByName('system_admin', 'api');

        $url = "/api/access-control/role/{$role->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete($url)
            ->assertStatus(200);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    #[TestDox('Delete a Role with bad ID')]
    #[Test]
    public function delete_role_with_bad_id()
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $role = RoleModel::findByName('system_admin', 'api');
        $url = '/api/access-control/role/999999';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->delete($url);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Role with ID 999999 not found.',
        ]);
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'system_admin', 'guard_name' => 'api']);
    }

    #[TestDox('Cannot delete a Permission with guest user (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_delete_a_role_with_unauthenticated(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $role = RoleModel::findByName('system_admin', 'api');
        $url = "/api/access-control/role/{$role->id}";
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->deleteJson($url);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'user.unauthenticated',
        ]);
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'system_admin', 'guard_name' => 'api']);
    }

    #[TestDox('Cannot delete a Permission with unauthorized user (UNAUTHORIZED)')]
    #[Test]
    public function can_not_delete_a_role_with_unauthorized(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystemAdmin);

        $role = RoleModel::findByName('system_admin', 'api');
        $url = "/api/access-control/role/{$role->id}";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->deleteJson($url);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'user.unauthorized',
        ]);
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'system_admin', 'guard_name' => 'api']);
    }
}
