<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SyncRolesToUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can sync role to user when user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_sync_roles_to_user_when_user_guest(): void
    {
        $data = ['name' => 'new_permission', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Can sync role to user when unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_sync_role_to_role_user_when_unauthorized(): void
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
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Sync role to permission user when super user (OK)')]
    #[Test]
    public function sync_role_to_user_super_user(): void
    {
        $newUser = UserModel::factory()->create();
        $roleSystemManager = Role::findByName('operations_manager', 'api');

        $data = [
            'userId' => $newUser->id,
            'roleIds' => [$roleSystemManager->id],
        ];
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.updated',
                'data' => true,
            ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $roleSystemManager->id,
            'model_type' => UserModel::class,
            'model_id' => $newUser->id,
        ]);
    }

    #[TestDox('Sync role to permission user when super user (OK)')]
    #[Test]
    public function sync_role_to_user_authorized(): void
    {
        $newUser = UserModel::factory()->create();
        $roleCondominiumUser = Role::findByName('field_supervisor', 'api');

        $data = [
            'userId' => $newUser->id,
            'roleIds' => [$roleCondominiumUser->id],
        ];
        $userSystemAdmin = UserModel::factory()->create();
        $token = $this->getToken('system_admin', $userSystemAdmin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'message' => 'success.updated',
                'data' => true,
            ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $roleCondominiumUser->id,
            'model_type' => UserModel::class,
            'model_id' => $newUser->id,
        ]);
    }

    #[TestDox('Sync role to permission user without data (NO UPDATED)')]
    #[Test]
    public function sync_role_to_user_without_data(): void
    {
        $data = [];
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'ok' => false,
                'message' => 'error.validation.failed',
                'data' => [
                    'userId' => ['The user id field is required.'],
                ],
            ]);
    }

    #[TestDox('Remove all role to permission user without data (NO UPDATED)')]
    #[Test]
    public function remove_all_role_to_user(): void
    {
        $user = UserModel::factory()->create();
        $roleCondominiumUser = Role::findByName('field_supervisor', 'api');
        $roleSystemManager = Role::findByName('operations_manager', 'api');
        $user->assignRole($roleCondominiumUser);
        $user->assignRole($roleSystemManager);

        $data = [
            'userId' => $user->id,
            'roleIds' => [],
        ];
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'ok' => true,
                'message' => 'success.updated',
                'data' => true,
            ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'model_type' => UserModel::class,
            'model_id' => $user->id,
        ]);
    }

    #[TestDox('Can not Sync role to permission user when have bad user id (NO UPDATED)')]
    #[Test]
    public function can_not_sync_role_to_user_when_bad_user_id(): void
    {
        $newUser = UserModel::factory()->create();
        $roleCondominiumUser = Role::findByName('field_supervisor', 'api');

        $data = [
            'userId' => 123123,
            'roleIds' => [$roleCondominiumUser->id],
        ];
        $userSystemAdmin = UserModel::factory()->create();
        $token = $this->getToken('system_admin', $userSystemAdmin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->putJson('/api/user/sync-roles-to-user', $data);
        $response->assertStatus(422)
            ->assertExactJson([
                'ok' => false,
                'message' => 'error.validation.failed',
                'data' => [
                    'userId' => ['The user id field must be a valid UUID.'],
                ],
            ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $roleCondominiumUser->id,
            'model_type' => UserModel::class,
            'model_id' => $newUser->id,
        ]);
    }
}
