<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use DatabaseTransactions;

    #[TestDox('Create user with correct data (OK)')]
    #[Test]
    public function create_user_check_entity(): void
    {
        //        config(['mail.default' => 'smtp']);
        MailTemplateModel::factory()->create([
            'locale' => 'es',
            'key' => 'welcome-user',
        ]);
        $data = [
            'name' => 'Name',
            'lastname' => 'Lastname',
            'email' => 'email@email.com',
            'password' => 'Password',
            'phone' => 'Phone',
        ];

        $response = $this->postJson('/api/user', $data)
            ->assertStatus(201);

        $this->assertInstanceOf(UserResource::class, $response->getOriginalContent()['data']);
        $this->assertInstanceOf(User::class, $response->getOriginalContent()['data']->resource);
    }

    #[TestDox('Create user check response structure')]
    #[Test]
    public function create_user_check_structure(): void
    {
        //        config(['mail.default' => 'smtp']);
        MailTemplateModel::factory()->create();
        $data = [
            'name' => 'Name',
            'lastname' => 'Lastname',
            'email' => 'email@email.com',
            'password' => 'Password',
            'phone' => 'Phone',
        ];

        $response = $this->post('/api/user', $data);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'ok',
                'message',
                'data' => [
                    'id',
                    'name',
                    'lastname',
                    'email',
                    'phone',
                    'isActive',
                    'language',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
        $response->assertJsonFragment([
            'name' => 'Name',
            'lastname' => 'Lastname',
            'email' => 'email@email.com',
            'phone' => 'Phone',
            'isActive' => true,
            'language' => 'en',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Name',
            'lastname' => 'Lastname',
            'email' => 'email@email.com',
            'phone' => 'Phone',
            'is_active' => true,
            'language' => 'en',
        ]);
        $this->assertDatabaseHas('send_notifications', [
            'to' => $data['email'],
        ]);
    }

    #[TestDox('Create user with missing fields (NO registered)')]
    #[Test]
    public function create_user_with_missing_field(): void
    {
        $data = [
            'name' => '',
            'lastname' => '',
            'email' => '',
            'password' => '',
            'phone' => '',
        ];

        $response = $this->post('/api/user', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'message',
                'data',
            ]);
        $response->assertJsonFragment([
            'name' => [
                0 => 'The name field is required.',
            ],
            'lastname' => [
                0 => 'The lastname field is required.',
            ],
            'email' => [
                0 => 'The email field is required.',
            ],
            'password' => [
                0 => 'The password field is required.',
            ],
            'phone' => [
                0 => 'The phone field is required.',
            ],
        ]);
    }
}
