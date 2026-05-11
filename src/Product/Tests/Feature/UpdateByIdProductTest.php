<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Resources\ProductResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateByIdProductTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Update Product by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function update_product_by_id_with_user_guest(): void
    {
        $product = ProductModel::factory()->create();

        $data = [
            'name' => 'Updated Value 1',
            'isActive' => false,
            'order' => 101,
        ];

        $response = $this->patchJson("/api/product/{$product->id}", $data)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not update Product by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_update_product_by_id_with_user_unauthorized(): void
    {
        $product = ProductModel::factory()->create();
        $token = $this->getToken('field_supervisor');

        $data = [
            'name' => 'Updated Value 1',
            'isActive' => false,
            'order' => 101,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->patchJson("/api/product/{$product->id}", $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can update Product by ID when user is authorized (OK)')]
    #[Test]
    public function can_update_product_by_id_if_is_authorized(): void
    {
        $product = ProductModel::factory()->create();
        $token = $this->getToken('system_admin');

        $data = [
            'name' => 'Updated Value 1',
            'isActive' => false,
            'order' => 101,
        ];

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/product/{$product->id}", $data)
            ->assertStatus(200);
    }

    #[TestDox('Update Product by ID, verify structure and type')]
    #[Test]
    public function update_product_by_id_with_correct_data(): void
    {
        $product = ProductModel::factory()->create();

        $data = [
            'name' => 'Updated Value 1',
            'isActive' => false,
            'order' => 101,
        ];

        $url = "/api/product/{$product->id}";

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'isActive',
                'order',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.updated', $responseOriginal['message']);
        $this->assertInstanceOf(ProductResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Product::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('products', [
            'name' => 'Updated Value 1',
            'is_active' => false,
            'order' => 101,
        ]);

    }

    #[TestDox('Update Product by ID,with only one field')]
    #[Test]
    public function update_product_by_id_with_only_one_field(): void
    {
        $product = ProductModel::factory()->create();

        $data = [
            'order' => 100,
        ];

        $url = "/api/product/{$product->id}";

        $token = $this->getToken('super_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson($url, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,

        ]);

    }

    #[TestDox('Update Product with bad ID, expect 404')]
    #[Test]
    public function update_product_with_bad_id(): void
    {
        $product = ProductModel::factory()->create();

        $data = [
            'order' => 100,
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson('/api/product/999999', $data)
            ->assertStatus(404);

        $this->assertequals('Product with ID 999999 not found.', $response->json()['message']);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'order' => 100,

        ]);
    }
}
