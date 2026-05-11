<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\RoleResource;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindAllRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('find all roles with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_all_roles_with_user_guest(): void
    {
        $url = '/api/access-control/role';
        $response = $this->getJson($url)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find all roles with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_all_roles_with_user_unauthorized(): void
    {
        $url = '/api/access-control/role';
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson($url);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find all roles when user is authorized (OK)')]
    #[Test]
    public function can_find_all_roles_if_is_authorized(): void
    {
        $url = '/api/access-control/role';
        $token = $this->getToken('system_admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson($url);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'ok' => true,
                'message' => 'success.search',
            ]);
    }

    #[TestDox('Find all Role, verify structure and type')]
    #[Test]
    public function find_all_role(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $roleWithUsers = RoleModel::create(['name' => 'role_with_users_count', 'guard_name' => 'api']);
        $userWithRoleA = UserModel::factory()->create();
        $userWithRoleB = UserModel::factory()->create();
        $userWithRoleA->assignRole($roleWithUsers);
        $userWithRoleB->assignRole($roleWithUsers);

        $token = Token::generateToken($userSuperAdmin);
        $roles = [
            'super1_admin',
            'system1_admin',
            'system1_manager',
            'condominium1_admin',
            'condominium1_manager',
            'condominium1_user',
        ];

        foreach ($roles as $role) {
            RoleModel::create(['name' => $role, 'guard_name' => 'api']);
        }

        $url = '/api/access-control/role';
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
                    'usersCount',
                    'createdAt',
                    'updatedAt',
                ],
            ],
        ]);

        $rolesCount = RoleModel::all()->count();

        $original_data = $response->getOriginalContent();
        $this->assertInstanceOf(RoleResource::class, $original_data['data'][0]);
        $this->assertInstanceOf(Role::class, $original_data['data'][0]->resource);
        $this->assertCount($rolesCount, $original_data['data']);
        $roleWithUsersResponse = collect($original_data['data'])
            ->first(fn (RoleResource $resource) => $resource->resource->getName() === 'role_with_users_count');
        $this->assertNotNull($roleWithUsersResponse);
        $this->assertSame(2, $roleWithUsersResponse->resource->getUsersCount());
    }
}
