<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Role>
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->slug(2, '_'),
            'guard_name' => 'api',
        ];
    }
}
