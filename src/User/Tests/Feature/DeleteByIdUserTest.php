<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class DeleteByIdUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Delete a User by Authorized user')]
    #[Test]
    public function delete_user_by_authorized_user(): void
    {
        $user = User::factory()->create();
        $token = $this->getToken('system_admin');
        $url = "/api/user/{$user->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->delete($url)
            ->assertStatus(200);
    }

    #[TestDox('can not  Delete a User by Unauthorized user')]
    #[Test]
    public function can_not_delete_user_by_unauthorized_user(): void
    {
        $user = User::factory()->create();
        $token = $this->getToken('field_supervisor');
        $url = "/api/user/{$user->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->delete($url)
            ->assertStatus(403);
    }

    #[TestDox('Delete a User by ID')]
    #[Test]
    public function delete_user_by_id(): void
    {
        $user = User::factory()->create();
        $token = $this->getToken('super_admin');
        $url = "/api/user/{$user->id}";

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->delete($url)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[TestDox('Delete a User with bad ID')]
    #[Test]
    public function delete_user_with_bad_id(): void
    {
        $user = User::factory()->create();
        $token = $this->getToken('super_admin');
        $url = '/api/user/999999';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->delete($url);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User with data 999999 not found.',
        ]);
    }
}
