<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class DeleteByIdSupplierTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Delete Supplier by ID with user guest (UNAUTHENTICATED)')]
    #[Test]
    public function delete_supplier_by_id_with_user_guest(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->deleteJson("/api/supplier/{$supplier->id}")
            ->assertStatus(401);

        $response->assertExactJson([
            'ok' => false,
            'message' => 'user.unauthenticated',
            'data' => null,
        ]);
    }

    #[TestDox('Can not delete Supplier by ID with user unauthorized (UNAUTHORIZED)')]
    #[Test]
    public function can_not_delete_supplier_by_id_with_user_unauthorized(): void
    {
        $supplier = Supplier::factory()->create();
        $token = $this->getToken('field_supervisor');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->deleteJson("/api/supplier/{$supplier->id}");

        $response->assertStatus(403)
            ->assertExactJson([
                'ok' => false,
                'message' => 'user.unauthorized',
                'data' => null,
            ]);
    }

    #[TestDox('Can delete Supplier by ID when user is authorized (OK)')]
    #[Test]
    public function can_delete_supplier_by_id_if_is_authorized(): void
    {
        $supplier = Supplier::factory()->create();
        $token = $this->getToken('system_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson("/api/supplier/{$supplier->id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('suppliers', ['id' => $supplier->id]);
    }

    #[TestDox('Delete a Supplier by ID')]
    #[Test]
    public function delete_supplier_by_id(): void
    {
        $supplier = Supplier::factory()->create();
        $token = $this->getToken('super_admin');

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson("/api/supplier/{$supplier->id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('suppliers', ['id' => $supplier->id]);
    }

    #[TestDox('Delete a Supplier with bad ID')]
    #[Test]
    public function delete_supplier_with_bad_id(): void
    {
        $token = $this->getToken('super_admin');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->deleteJson('/api/supplier/999999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Supplier with ID 999999 not found.',
        ]);
    }
}
