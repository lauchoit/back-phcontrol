<?php

// tests/Unit/Infrastructure/Resources/UserResourceTest.php

// declare(strict_types=1);

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Infrastructure\Resources;

use Illuminate\Http\Request;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class UserResourceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    #[TestDox('Devuelve el array mapeado correctamente cuando todos los campos tienen valor')]
    public function returns_full_payload_when_all_fields_present(): void
    {
        $user = new User(
            id: '550e8400-e29b-41d4-a716-446655440000',
            name: 'Jesus',
            lastname: 'Laucho',
            email: 'contacto@lauchoit.com',
            password: '12345678',
            phone: '7866030022',
            isActive: true,
            language: 'es',
            createdAt: '2025-10-01T10:20:30Z',
            updatedAt: '2025-10-02T11:22:33Z',
        );
        $request = new Request;
        $resource = UserResource::make($user);
        $result = $resource->toArray($request);
        $this->assertSame([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Jesus',
            'lastname' => 'Laucho',
            'email' => 'contacto@lauchoit.com',
            'phone' => '7866030022',
            'isActive' => true,
            'language' => 'es',
            'createdAt' => '2025-10-01T10:20:30Z',
            'updatedAt' => '2025-10-02T11:22:33Z',
        ], $result);
        $this->assertArrayHasKey('isActive', $result); // mantiene booleanos
    }

    #[Test]
    #[TestDox('Filtra claves con valores vacíos o nulos según la lógica del array_filter')]
    public function filters_empty_and_null_values(): void
    {
        $user = new User(
            id: '550e8400-e29b-41d4-a716-446655440000',
            name: '',
            lastname: '',
            email: 'contacto@lauchoit.com',
            password: '12345678',
            phone: '',
            isActive: false,
            language: 'es',
            createdAt: '2025-10-01T10:20:30Z',
            updatedAt: '2025-10-02T11:22:33Z',
        );

        $request = new Request;
        $resource = new UserResource($user);
        $result = $resource->toArray($request);

        // Claves eliminadas
        $this->assertArrayNotHasKey('name', $result);
        $this->assertArrayNotHasKey('lastname', $result);
        $this->assertArrayNotHasKey('phone', $result);

        // Claves presentes
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $result['id']);
        $this->assertSame('contacto@lauchoit.com', $result['email']);
        $this->assertFalse($result['isActive']);
        $this->assertSame('2025-10-01T10:20:30Z', $result['createdAt']);
        $this->assertSame('2025-10-02T11:22:33Z', $result['updatedAt']);
    }
}
