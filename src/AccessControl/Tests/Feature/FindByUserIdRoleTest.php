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

class FindByUserIdRoleTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Can not find roles by user ID when user is guest')]
    #[Test]
    public function can_not_find_roles_by_user_id_when_guest(): void
    {
        $user = UserModel::factory()->create();

        $response = $this->getJson("/api/access-control/role/user/{$user->id}");

        $response->assertStatus(401)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthenticated',
                'data' => null,
            ]);
    }

    #[TestDox('Find roles by user ID and verify structure and type')]
    #[Test]
    public function find_roles_by_user_id_and_verify_structure_and_type(): void
    {
        $user = UserModel::factory()->create();
        $roleA = RoleModel::findByName('operations_manager', 'api');
        $roleB = RoleModel::findByName('field_supervisor', 'api');
        $user->assignRole([$roleA, $roleB]);

        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/access-control/role/user/{$user->id}");

        $response->assertStatus(200)
            ->assertExactJsonStructure([
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

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertEquals('success.search', $responseOriginal['message']);
        $this->assertCount(2, $responseOriginal['data']);
        $this->assertInstanceOf(RoleResource::class, $responseOriginal['data'][0]);
        $this->assertInstanceOf(Role::class, $responseOriginal['data'][0]->resource);
    }

    #[TestDox('Can not find roles by user ID when unauthorized')]
    #[Test]
    public function can_not_find_roles_by_user_id_when_unauthorized(): void
    {
        $user = UserModel::factory()->create();
        $userSystemAdmin = UserModel::factory()->create();
        $roleSystemAdmin = RoleModel::findByName('system_admin', 'api');
        $userSystemAdmin->assignRole($roleSystemAdmin);

        $token = Token::generateToken($userSystemAdmin);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/access-control/role/user/{$user->id}");

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can not find roles by invalid user ID')]
    #[Test]
    public function can_not_find_roles_by_invalid_user_id(): void
    {
        $userSuperAdmin = UserModel::factory()->create();
        $roleSuperAdmin = RoleModel::findByName('super_admin', 'api');
        $userSuperAdmin->assignRole($roleSuperAdmin);

        $token = Token::generateToken($userSuperAdmin);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/access-control/role/user/999999');

        $response->assertStatus(404)
            ->assertJson([
                'ok' => false,
                'message' => 'User with data 999999 not found.',
            ]);
    }
}
