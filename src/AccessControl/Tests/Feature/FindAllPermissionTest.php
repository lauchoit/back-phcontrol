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

class FindAllPermissionTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find all Permission, verify structure and type')]
    #[Test]
    public function find_all_permission(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);
        $permisions = [
            'super1_admin',
            'system1_admin',
            'system1_manager',
            'condominium1_admin',
            'condominium1_manager',
            'condominium1_user',
        ];

        foreach ($permisions as $permision) {
            PermissionModel::create(['name' => $permision, 'guard_name' => 'api']);
        }

        $url = '/api/access-control/permission';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url);
        $response->assertStatus(200);
        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guardName',
                    'createdAt',
                    'updatedAt',
                ],
            ],
        ]);

        $totalPermissions = PermissionModel::count();
        $original_data = $response->getOriginalContent();
        $this->assertInstanceOf(PermissionResource::class, $original_data['data'][0]);
        $this->assertInstanceOf(Permission::class, $original_data['data'][0]->resource);
        $this->assertCount($totalPermissions, $original_data['data']);
    }

    #[TestDox('Find all Permission, only authorized user (OK)')]
    #[Test]
    public function find_all_permission_authorized_user(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = $this->getToken('system_admin');
        $permisions = [
            'super1_admin',
            'system1_admin',
            'system1_manager',
            'condominium1_admin',
            'condominium1_manager',
            'condominium1_user',
        ];

        foreach ($permisions as $permision) {
            PermissionModel::create(['name' => $permision, 'guard_name' => 'api']);
        }

        $url = '/api/access-control/permission';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url);
        $response->assertStatus(200);
    }

    #[TestDox('can not Find all Permission, only unauthorized user (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_all_permission_unauthorized_user(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = $this->getToken('field_supervisor');
        $permisions = [
            'super1_admin',
            'system1_admin',
            'system1_manager',
            'condominium1_admin',
            'condominium1_manager',
            'condominium1_user',
        ];

        foreach ($permisions as $permision) {
            PermissionModel::create(['name' => $permision, 'guard_name' => 'api']);
        }

        $url = '/api/access-control/permission';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url);
        $response->assertStatus(403);
    }
}
