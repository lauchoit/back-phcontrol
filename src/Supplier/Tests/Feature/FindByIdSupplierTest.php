<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources\SupplierResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class FindByIdSupplierTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Find Supplier by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function find_by_id_supplier_with_user_guest(): void
    {
        $supplier = SupplierModel::factory()->create();

        $response = $this->getJson("/api/supplier/{$supplier->id}")
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not find Supplier by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_find_by_id_supplier_with_user_unauthorized(): void
    {
        $supplier = SupplierModel::factory()->create();
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->getJson("/api/supplier/{$supplier->id}");

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can find Supplier by ID when user is authorized (OK)')]
    #[Test]
    public function can_find_by_id_supplier_if_is_authorized(): void
    {
        $supplier = SupplierModel::factory()->create();
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/supplier/{$supplier->id}")
            ->assertStatus(200);
    }

    #[TestDox('Find Supplier by ID, verify structure and type')]
    #[Test]
    public function find_by_id_and_verify_structure_and_type(): void
    {
        $suppliers = SupplierModel::factory()->count(3)->create();
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/supplier/{$suppliers[0]->id}")
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'phone',
                'createdAt',
                'updatedAt',
            ],
        ]);

        $responseOriginal = $response->getOriginalContent();
        $this->assertTrue($responseOriginal['ok']);
        $this->assertequals('success.search', $responseOriginal['message']);
        $this->assertInstanceOf(SupplierResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Supplier::class, $responseOriginal['data']->resource);
    }

    #[TestDox('Find Supplier by invalid ID, expect 404 error')]
    #[Test]
    public function find_with_invalid_id(): void
    {
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/supplier/999999')
            ->assertStatus(404);

        $responseOriginal = $response->getOriginalContent();
        $this->assertFalse($responseOriginal['ok']);
        $this->assertequals('error.not_found', $responseOriginal['message']);
    }
}
