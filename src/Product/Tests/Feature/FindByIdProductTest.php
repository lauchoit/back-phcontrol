<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Resources\ProductResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindByIdProductTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find Product by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_by_id_product_with_user_guest(): void
    {
        $product = ProductModel::factory()->create();

        $response = $this->getJson("/api/product/{$product->id}")
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find Product by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_by_id_product_with_user_unauthorized(): void
    {
        $product = ProductModel::factory()->create();
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson("/api/product/{$product->id}");

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find Product by ID when user is authorized (OK)')]
    #[Test]
    public function can_find_by_id_product_if_is_authorized(): void
    {
        $product = ProductModel::factory()->create();
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/product/{$product->id}")
            ->assertStatus(200);
    }

    #[TestDox('Find Product by ID, verify structure and type')]
    #[Test]
    public function find_by_id_and_verify_structure_and_type(): void
    {
        $products = ProductModel::factory()->count(3)->create();

        $url = "/api/product/{$products[0]->id}";

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url)
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
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(ProductResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Product::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find Product by invalid ID, expect 404 error')]
    #[Test]
    public function find_with_invalid_id(): void
    {
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/product/999999')
            ->assertStatus(404);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('error.not_found', $responseOriginal['message']);
    }
}
