<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RevokePermissionFromUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not revoke role to permission user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_revoke_permission_from_user_guest(): void
    {
        $userSystem = UserModel::factory()->create();
        $roleSystemAdmin = Role::findByName('system_admin', 'api');
        $userSystem->assignRole($roleSystemAdmin);

        $data = ['userId' => 1, 'permissionIds' => 3];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission/revoke-permissions-to-user', $data);
        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Can not revoke permission from user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_revoke_permission_from_user_unauthorized(): void
    {
        $userSystem = UserModel::factory()->create();
        $roleSystemAdmin = Role::findByName('field_supervisor', 'api');
        $userSystem->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystem);

        $data = ['userId' => $userSystem->id, 'permissionIds' => []];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission/revoke-permissions-to-user', $data);
        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Revoke permission from user for assigned permission (ok)')]
    #[Test]
    public function add_permission_to_user_for_system_admin(): void
    {
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = Role::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $permissionDeletePermission = Permission::findByName('permissions.delete.by.id', 'api');
        $userSystemAdmin->givePermissionTo($permissionDeletePermission);

        $token = Token::generateToken($userSystemAdmin);

        $userSystem = UserModel::factory()->create();
        $permissionFindAll = Permission::findByName('user.find.all', 'api');
        $userSystem->givePermissionTo($permissionFindAll);

        $this->assertEquals('user.find.all', $userSystem->getPermissionNames()->first());

        $data = [
            'userId' => $userSystem->id,
            'permissionIds' => [$permissionFindAll->id],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission/revoke-permissions-to-user', $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.deleted',
                'data' => true,
            ]);

        $this->assertDatabaseMissing('model_has_permissions', [
            'model_id' => $data['userId'],
            'permission_id' => $permissionFindAll->id,
            'model_type' => UserModel::class,
        ]);

    }
}
