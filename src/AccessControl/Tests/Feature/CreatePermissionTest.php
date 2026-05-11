<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\PermissionResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CreatePermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not create permission user guest (UNAUTHENTICATED)')]
    #[Test]
    public function can_not_create_permission_user_guest(): void
    {
        $data = ['name' => 'new_permission', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission', $data);

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Can not create permission user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_create_permission_user_unauthorized(): void
    {
        $token = $this->getToken('field_supervisor');

        $data = ['name' => 'new_permission', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can not create permission user authorized (OK)')]
    #[Test]
    public function create_permission_user_authorized(): void
    {
        $token = $this->getToken('system_admin');

        $data = ['name' => 'new_permission', 'guardName' => 'api'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission', $data);

        $response->assertStatus(201);
    }

    #[TestDox('Create permission with correct data (OK)')]
    #[Test]
    public function create_permission_check_entity(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);
        $data = [
            'name' => 'new.permission',
            'guardName' => 'api',
        ];

        $token = Token::generateToken($userSuperAdmin);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/access-control/permission', $data)
            ->assertStatus(201);

        $this->assertInstanceOf(PermissionResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(Permission::class, $response->getOriginalContent()['data']->resource);
        $this->assertDatabaseHas('permissions', [
            'name' => 'new.permission',
            'guard_name' => 'api',
        ]);
    }

    #[TestDox('Create permission check response structure')]
    #[Test]
    public function create_permission_check_structure(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);
        $token = Token::generateToken($userSuperAdmin);
        $data = [
            'name' => 'new.permission',
            'guardName' => 'api',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'guardName',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('permissions', [
            'name' => 'new.permission',
            'guard_name' => 'api',
        ]);
    }

    #[TestDox('Create permission with missing fields (NO registered)')]
    #[Test]
    public function create_permission_with_missing_field(): void
    {
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $data = [
            'name' => '',
            'guard_name' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->post('/api/access-control/permission', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'name' => [
                0 => 'The name field is required.',
            ],
            'guardName' => [
                0 => 'The guard name field is required.',
            ],
        ]);
    }
}
