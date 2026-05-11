<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Resources\ProductResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Create product with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function create_product_with_user_guest(): void
    {
        $data = [
            'name' => 'Name',
            'isActive' => true,
            'order' => 1,
        ];

        $response = $this->postJson('/api/product', $data)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not create product with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_create_product_with_user_unauthorized(): void
    {
        $data = [
            'name' => 'Name',
            'isActive' => true,
            'order' => 1,
        ];

        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->postJson('/api/product', $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can create product when user is authorized (OK)')]
    #[Test]
    public function can_create_product_if_is_authorized(): void
    {
        $data = [
            'name' => 'Name',
            'isActive' => true,
            'order' => 1,
        ];

        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/product', $data)
            ->assertStatus(201);
    }

    #[TestDox('Create product with correct data (OK)')]
    #[Test]
    public function create_product_check_entity(): void
    {
        $data = [
            'name' => 'Name',
            'isActive' => true,
            'order' => 1,
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/product', $data)
            ->assertStatus(201);

        $this->assertInstanceOf(ProductResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(Product::class, $response->getOriginalContent()['data']->resource);
    }

    #[TestDox('Create product check response structure')]
    #[Test]
    public function create_product_check_structure(): void
    {
        $data = [
            'name' => 'Name',
            'isActive' => true,
            'order' => 1,
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/product', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'isActive',
                    'order',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('products', [
            'name' => 'Name',
            'is_active' => true,
            'order' => 1,
        ]);

    }

    #[TestDox('Create product with missing fields (NO registered)')]
    #[Test]
    public function create_product_with_missing_field(): void
    {
        $data = [
            'name' => '',
            'isActive' => '',
            'order' => '',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/product', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'name' => [
                0 => 'validation.required',
            ],
            'isActive' => [
                0 => 'validation.required',
            ],
            'order' => [
                0 => 'validation.required',
            ],
        ]);
    }
}
