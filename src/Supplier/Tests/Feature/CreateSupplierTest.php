<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources\SupplierResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CreateSupplierTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Create supplier with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function create_supplier_with_user_guest(): void
    {
        $data = [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $response = $this->postJson('/api/supplier', $data)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not create supplier with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_create_supplier_with_user_unauthorized(): void
    {
        $data = [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->postJson('/api/supplier', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can create supplier when user is authorized (OK)')]
    #[Test]
    public function can_create_supplier_if_is_authorized(): void
    {
        $data = [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/supplier', $data)
            ->assertStatus(201);
    }

    #[TestDox('Create supplier with correct data (OK)')]
    #[Test]
    public function create_supplier_check_entity(): void
    {
        $data = [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/supplier', $data)
            ->assertStatus(201);

        $this->assertInstanceOf(SupplierResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(Supplier::class, $response->getOriginalContent()['data']->resource);
        $this->assertTrue(Str::isUuid($response->json('data.id')));
    }

    #[TestDox('Create supplier check response structure')]
    #[Test]
    public function create_supplier_check_structure(): void
    {
        $data = [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/supplier', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'phone',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Name',
            'phone' => 'Phone',
        ]);
    }

    #[TestDox('Can create supplier without phone (OK)')]
    #[Test]
    public function can_create_supplier_without_phone(): void
    {
        $data = [
            'name' => 'Name',
        ];

        $token = $this->getToken('super_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/supplier', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Name',
            'phone' => null,
        ]);
    }

    #[TestDox('Create supplier with missing fields (NO registered)')]
    #[Test]
    public function create_supplier_with_missing_field(): void
    {
        $data = [
            'name' => '',
            'phone' => '',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/supplier', $data);

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
        ]);
        $response->assertJsonMissing([
            'phone' => [
                0 => 'The phone field is required.',
            ],
        ]);
    }
}
