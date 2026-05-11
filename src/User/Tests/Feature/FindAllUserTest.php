<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindAllUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find all User, verify structure and type')]
    #[Test]
    public function find_all_user(): void
    {
        UserModel::factory()->count(4)->create();
        $token = $this->getToken('super_admin');

        $url = '/api/user';
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
                    'lastname',
                    'email',
                    'phone',
                    'isActive',
                    'language',
                    'createdAt',
                    'updatedAt',
                ],
            ],
        ]);

        $original_data = $response->getOriginalContent();
        $this->assertInstanceOf(UserResource::class, $original_data['data'][0]);
        $this->assertInstanceOf(User::class, $original_data['data'][0]->resource);
        $this->assertCount(UserModel::count(), $original_data['data']);
    }

    #[TestDox('can not find all when user is not authorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_all_users_if_not_authorized(): void
    {
        $token = $this->getToken('field_supervisor');
        $url = '/api/user';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(403);

        $response->assertJson([
            'ok' => false,
            'message' => 'user.unauthorized',
            'data' => null,
        ]);
    }

    #[TestDox('Find all users when user is authorized (OK)')]
    #[Test]
    public function can_find_all_users_if_is_authorized(): void
    {
        $token = $this->getToken('system_admin');
        $url = '/api/user';

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
            ->assertStatus(200);
    }

    #[TestDox('Find all users when user with filters (OK)')]
    #[Test]
    public function can_find_all_users_with_filters(): void
    {
        $this->withExceptionHandling();
        foreach ($this->users as $userData) {
            UserModel::factory()->create($userData);
        }

        $token = $this->getToken('super_admin');
        $baseUrl = '/api/user';

        // Test name filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?name=Jesus')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test email filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?email=maria.garcia@example.com')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'maria.garcia@example.com');

        // Test phone filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?phone=7866030022')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test lastname filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?lastname=laucho')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test mixed filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?lastname=laucho&name=David')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');

    }

    #[TestDox('Find all users when user with partials filter (OK)')]
    #[Test]
    public function can_find_all_users_with_partial_filters(): void
    {
        $this->withExceptionHandling();

        foreach ($this->users as $userData) {
            UserModel::factory()->create($userData);
        }

        $token = $this->getToken('super_admin');
        $baseUrl = '/api/user';

        // Test name filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?name=Jesu')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test email filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?email=example.com')
            ->assertStatus(200)
            ->assertJsonCount(4, 'data');

        // Test phone filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?phone=7866030022')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test lastname filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?lastname=cho')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

        // Test mixed filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?lastname=laucho&name=vid')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');

    }

    #[TestDox('Can not Find all users when filter is invalid (NO FILTER)')]
    #[Test]
    public function can_not_find_all_users_with_invalid_filters(): void
    {
        $this->withExceptionHandling();

        $token = $this->getToken('super_admin');
        $baseUrl = '/api/user';

        // Test invalid filter
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?firstname=Jesus')
            ->assertStatus(400);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'error.invalid_filter: name, lastname, email, phone, is_active',
            'data' => null,
        ]);

    }

    #[TestDox('Remove filter in find all users with is empty')]
    #[Test]
    public function remove_filter_in_find_all_users_with_is_empty(): void
    {
        $this->withExceptionHandling();

        foreach ($this->users as $userData) {
            UserModel::factory()->create($userData);
        }

        $token = $this->getToken('super_admin');
        $baseUrl = '/api/user';

        // Test invalid filter
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($baseUrl.'?name=Jesu&lastname=')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Jesus');

    }

    private $users = [
        [
            'name' => 'Jesus',
            'lastname' => 'Laucho',
            'email' => 'contacto@lauchoit.com',
            'phone' => '7866030022',
        ],
        [
            'name' => 'John',
            'lastname' => 'Smith',
            'email' => 'john.smith@example.com',
            'phone' => '7866030023',
        ],
        [
            'name' => 'Maria',
            'lastname' => 'Garcia',
            'email' => 'maria.garcia@example.com',
            'phone' => '7866030024',
        ],
        [
            'name' => 'David',
            'lastname' => 'Johnson',
            'email' => 'david.johnson@example.com',
            'phone' => '7866030025',
        ],
        [
            'name' => 'Sarah',
            'lastname' => 'Williams',
            'email' => 'sarah.williams@example.com',
            'phone' => '7866030026',
        ],
    ];
}
