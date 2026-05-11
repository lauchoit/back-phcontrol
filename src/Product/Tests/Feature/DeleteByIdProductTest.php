<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class DeleteByIdProductTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Delete Product by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function delete_product_by_id_with_user_guest(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/product/{$product->id}")
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not delete Product by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_delete_product_by_id_with_user_unauthorized(): void
    {
        $product = Product::factory()->create();
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->deleteJson("/api/product/{$product->id}");

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can delete Product by ID when user is authorized (OK)')]
    #[Test]
    public function can_delete_product_by_id_if_is_authorized(): void
    {
        $product = Product::factory()->create();
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson("/api/product/{$product->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[TestDox('Delete a Product by ID')]
    #[Test]
    public function delete_product_by_id(): void
    {
        $product = Product::factory()->create();
        $token = $this->getToken('super_admin');

        $url = "/api/product/{$product->id}";
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson($url)
            ->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[TestDox('Delete a Product with bad ID')]
    #[Test]
    public function delete_product_with_bad_id(): void
    {
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson('/api/product/999999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Product with ID 999999 not found.',
        ]);
    }
}
