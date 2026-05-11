<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\Token;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindByIdUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find User by ID, verify structure and type')]
    #[Test]
    public function find_by_id_user_and_verify_structure_and_type(): void
    {
        // AAA
        //        Arange
        //        Act
        //        Assertion
        $users = UserModel::factory()->count(3)->create();

        $permission = Permission::findByName('user.find.by.id', 'api');
        $users[0]->givePermissionTo($permission);

        $url = "/api/user/{$users[0]->id}";

        $token = Token::generateToken($users[0]);
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson($url)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'lastname',
                'email',
                'phone',
                'isActive',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(UserResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(User::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find User by invalid ID, expect 404 error')]
    #[Test]
    public function find_user_with_invalid_id(): void
    {
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $url = '/api/user/999999'; // ID que no existe
        $permission = Permission::findByName('user.find.by.id', 'api');
        $user->givePermissionTo($permission);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson($url)
            ->assertStatus(404);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('User with data 999999 not found.', $responseOriginal['message']);
    }

    #[TestDox('Find User whithout permission, expect 403 error')]
    #[Test]
    public function find_without_permission_invalid_id(): void
    {
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $url = '/api/user/999999';
        $permission = Permission::findByName('user.find.all', 'api');
        $user->givePermissionTo($permission);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson($url)
            ->assertStatus(403);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('user.unauthorized', $responseOriginal['message']);
    }

    #[TestDox('Find User whithout permission, expect 200')]
    #[Test]
    public function find_own_user_id(): void
    {
        $user = UserModel::factory()->create();
        $token = Token::generateToken($user);
        $url = "/api/user/{$user->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson($url)
            ->assertStatus(200);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(UserResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(User::class, $responseOriginal['data']->resource);
    }
}
