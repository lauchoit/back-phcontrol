<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'lastname' => $this->faker->word,
            'email' => $this->faker->unique()->email(),
            'password' => bcrypt('password'), // password
            'phone' => $this->faker->e164PhoneNumber(),
            'is_active' => true,
            'language' => 'en',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
