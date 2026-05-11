<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources\SupplierResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateByIdSupplierTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Update Supplier by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function update_supplier_by_id_with_user_guest(): void
    {
        $supplier = SupplierModel::factory()->create();

        $data = [
            'name' => 'Updated Value 1',
            'phone' => 'Updated Value 2',
        ];

        $response = $this->patchJson("/api/supplier/{$supplier->id}", $data)
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not update Supplier by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_update_supplier_by_id_with_user_unauthorized(): void
    {
        $supplier = SupplierModel::factory()->create();
        $token = $this->getToken('field_supervisor');

        $data = [
            'name' => 'Updated Value 1',
            'phone' => 'Updated Value 2',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->patchJson("/api/supplier/{$supplier->id}", $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can update Supplier by ID when user is authorized (OK)')]
    #[Test]
    public function can_update_supplier_by_id_if_is_authorized(): void
    {
        $supplier = SupplierModel::factory()->create();
        $token = $this->getToken('system_admin');

        $data = [
            'name' => 'Updated Value 1',
            'phone' => 'Updated Value 2',
        ];

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/supplier/{$supplier->id}", $data)
            ->assertStatus(200);
    }

    #[TestDox('Update Supplier by ID, verify structure and type')]
    #[Test]
    public function update_supplier_by_id_with_correct_data(): void
    {
        $supplier = SupplierModel::factory()->create();

        $data = [
            'name' => 'Updated Value 1',
            'phone' => 'Updated Value 2',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/supplier/{$supplier->id}", $data)
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
        $this->assertequals('success.updated', $responseOriginal['message']);
        $this->assertInstanceOf(SupplierResource::class, $responseOriginal['data']);
        $this->assertInstanceOf(Supplier::class, $responseOriginal['data']->resource);
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Updated Value 1',
            'phone' => 'Updated Value 2',
        ]);
    }

    #[TestDox('Update Supplier by ID,with only one field')]
    #[Test]
    public function update_supplier_by_id_with_only_one_field(): void
    {
        $supplier = SupplierModel::factory()->create();

        $data = [
            'name' => 'Updated Value 2',
        ];

        $token = $this->getToken('super_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/supplier/{$supplier->id}", $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Value 2',
        ]);
    }

    #[TestDox('Update Supplier phone with null (OK)')]
    #[Test]
    public function update_supplier_phone_with_null(): void
    {
        $supplier = SupplierModel::factory()->create();
        $token = $this->getToken('super_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/supplier/{$supplier->id}", [
            'phone' => null,
        ])->assertStatus(200);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'phone' => null,
        ]);
    }

    #[TestDox('Update Supplier with bad ID, expect 404')]
    #[Test]
    public function update_supplier_with_bad_id(): void
    {
        $supplier = SupplierModel::factory()->create();

        $data = [
            'name' => 'Updated Value 2',
        ];

        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson('/api/supplier/999999', $data)
            ->assertStatus(404);

        $this->assertequals('Supplier with ID 999999 not found.', $response->json()['message']);

        $this->assertDatabaseMissing('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Value 2',
        ]);
    }
}
