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

class FindAuthenticatedUserPermissionsTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not find authenticated user permissions when user is guest')]
    #[Test]
    public function can_not_find_authenticated_user_permissions_when_guest(): void
    {
        $response = $this->getJson('/api/access-control/permission/authenticated-user');

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Find authenticated user permissions from roles and direct permissions')]
    #[Test]
    public function find_authenticated_user_permissions_from_roles_and_direct_permissions(): void
    {
        $user = UserModel::factory()->create();
        $role = RoleModel::create(['name' => 'test_authenticated_user_role', 'guard_name' => 'api']);

        $permissionFromRole = PermissionModel::create(['name' => 'test.permission.from.role', 'guard_name' => 'api']);
        $sharedPermission = PermissionModel::create(['name' => 'test.permission.shared', 'guard_name' => 'api']);
        $directPermission = PermissionModel::create(['name' => 'test.permission.direct', 'guard_name' => 'api']);

        $role->syncPermissions([$permissionFromRole->id, $sharedPermission->id]);
        $user->assignRole($role);
        $user->givePermissionTo([$sharedPermission->id, $directPermission->id]);

        $token = Token::generateToken($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/access-control/permission/authenticated-user');

        $response->assertStatus(200)
            ->assertExactJsonStructure([
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

        $originalData = $response->getOriginalContent();
        $permissionNames = collect($originalData['data'])
            ->map(fn (PermissionResource $resource) => $resource->resource->getName())
            ->values();

        $this->assertTrue($originalData['ok']);
        $this->assertEquals('success.search', $originalData['message']);
        $this->assertCount(3, $originalData['data']);
        $this->assertInstanceOf(PermissionResource::class, $originalData['data'][0]);
        $this->assertInstanceOf(Permission::class, $originalData['data'][0]->resource);
        $this->assertTrue($permissionNames->contains('test.permission.from.role'));
        $this->assertTrue($permissionNames->contains('test.permission.shared'));
        $this->assertTrue($permissionNames->contains('test.permission.direct'));
    }

    #[TestDox('Find all permissions when authenticated user is super admin')]
    #[Test]
    public function find_all_permissions_when_authenticated_user_is_super_admin(): void
    {
        $user = UserModel::factory()->create();
        $role = RoleModel::findOrCreate('super_admin', 'api');

        $firstPermission = PermissionModel::create(['name' => 'test.super.admin.first', 'guard_name' => 'api']);
        $secondPermission = PermissionModel::create(['name' => 'test.super.admin.second', 'guard_name' => 'api']);

        $user->assignRole($role);
        $token = Token::generateToken($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/access-control/permission/authenticated-user');

        $response->assertStatus(200)
            ->assertExactJsonStructure([
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

        $originalData = $response->getOriginalContent();
        $permissionNames = collect($originalData['data'])
            ->map(fn (PermissionResource $resource) => $resource->resource->getName())
            ->values();

        $this->assertTrue($permissionNames->contains($firstPermission->name));
        $this->assertTrue($permissionNames->contains($secondPermission->name));
    }
}
