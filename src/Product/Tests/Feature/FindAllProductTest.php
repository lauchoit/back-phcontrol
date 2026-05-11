<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Resources\ProductResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindAllProductTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find all product with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_all_product_with_user_guest(): void
    {
        $response = $this->getJson('/api/product')
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find all product with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_all_product_with_user_unauthorized(): void
    {
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson('/api/product');

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find all product when user is authorized (OK)')]
    #[Test]
    public function can_find_all_product_if_is_authorized(): void
    {
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/product')
            ->assertStatus(200);
    }

    #[TestDox('Find all Product, verify structure and type')]
    #[Test]
    public function find_all_product(): void
    {
        ProductModel::factory()->count(5)->create();

        $token = $this->getToken('super_admin');

        $url = '/api/product';
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson($url);
        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'isActive',
                    'order',
                    'createdAt',
                    'updatedAt',
                ],
            ],
        ]);

        $original_data = $response->getOriginalContent();
        $this->assertInstanceOf(ProductResource::class, $original_data['data'][0]);
        $this->assertInstanceOf(Product::class, $original_data['data'][0]->resource);
        $this->assertCount(5, $original_data['data']);
        $response->assertStatus(200);
    }
}
