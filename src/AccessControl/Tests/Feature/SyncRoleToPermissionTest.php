<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SyncRoleToPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not add role to permission user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_add_role_to_permission_user_guest(): void
    {
        $data = ['name' => 'new_permission', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->patchJson('/api/access-control/permission/sync-permissions-to-role', $data);

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Can not sync role to permission user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_sync_role_to_permission_user_unauthorized(): void
    {
        $role = Role::findByName('field_supervisor', 'api');
        $permissions = PermissionModel::limit(5)->get()->pluck('id')->toArray();
        $data = [
            'roleId' => $role->id,
            'permissionIds' => $permissions,
        ];

        $token = $this->getToken('field_supervisor');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->patchJson('/api/access-control/permission/sync-permissions-to-role', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Add role to permission user for system_manager (ok)')]
    #[Test]
    public function add_role_to_permission_user_for_system_manager(): void
    {
        $token = $this->getToken('super_admin');

        $role = Role::findByName('field_supervisor', 'api');
        $permissions = PermissionModel::limit(4)->get()->pluck('id')->toArray();

        $data = [
            'roleId' => $role->id,
            'permissionIds' => $permissions,
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->patch('/api/access-control/permission/sync-permissions-to-role', $data);
        $response->assertStatus(201)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.added',
                'data' => true,
            ]);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissions[0],
            'role_id' => $role->id,
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissions[1],
            'role_id' => $role->id,
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissions[2],
            'role_id' => $role->id,
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissions[3],
            'role_id' => $role->id,
        ]);
    }

    #[TestDox('Remove all permission from role (ok)')]
    #[Test]
    public function remove_all_permission_from_role(): void
    {
        $token = $this->getToken('super_admin');

        $role = Role::findByName('field_supervisor', 'api');

        $data = [
            'roleId' => $role->id,
            'permissionIds' => [],
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->patch('/api/access-control/permission/sync-permissions-to-role', $data);
        $response->assertStatus(201)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.added',
                'data' => true,
            ]);

        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
        ]);
    }
}
