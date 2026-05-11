<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources\SupplierResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindAllSupplierTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find all supplier with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_all_supplier_with_user_guest(): void
    {
        $response = $this->getJson('/api/supplier')
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find all supplier with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_all_supplier_with_user_unauthorized(): void
    {
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson('/api/supplier');

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find all supplier when user is authorized (OK)')]
    #[Test]
    public function can_find_all_supplier_if_is_authorized(): void
    {
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/supplier')
            ->assertStatus(200);
    }

    #[TestDox('Find all Supplier, verify structure and type')]
    #[Test]
    public function find_all_supplier(): void
    {
        SupplierModel::factory()->count(5)->create();

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/supplier');

        $response->assertExactJsonStructure([
            'ok',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'phone',
                    'createdAt',
                    'updatedAt',
                ],
            ],
        ]);

        $originalData = $response->getOriginalContent();
        $this->assertInstanceOf(SupplierResource::class, $originalData['data'][0]);
        $this->assertInstanceOf(Supplier::class, $originalData['data'][0]->resource);
        $this->assertCount(5, $originalData['data']);
        $response->assertStatus(200);
    }
}
