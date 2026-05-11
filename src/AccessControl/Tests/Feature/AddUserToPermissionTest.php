<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class AddUserToPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not add role to permission user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_add_permission_to_user_guest(): void
    {
        $data = ['name' => 'new_permission', 'guardName' => 'api'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/access-control/permission/add-permissions-to-user', $data);

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Can not add role to permission user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_add_user_to_permission_user_unauthorized(): void
    {
        $permissionsDB = Permission::first();
        $userSystem = UserModel::factory()->create();
        $token = $this->getToken('field_supervisor');
        $data = [
            'userId' => $userSystem->id,
            'permissionIds' => [$permissionsDB->first()->id],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission/add-permissions-to-user', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Add permission to user for system_admin (ok)')]
    #[Test]
    public function add_permission_to_user_for_system_admin(): void
    {
        $token = $this->getToken('super_admin');
        $userSystem = UserModel::factory()->create();
        $permissionsDB = Permission::first();

        $data = [
            'userId' => $userSystem->id,
            'permissionIds' => [$permissionsDB->first()->id],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission/add-permissions-to-user', $data);

        $response->assertStatus(201)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.added',
                'data' => true,
            ]);

        $this->assertDatabaseHas('model_has_permissions', [
            'model_id' => $data['userId'],
            'permission_id' => $permissionsDB->first()->id,
            'model_type' => UserModel::class,
        ]);
    }
}
