<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\AccessControl;

/**
 * @extends Factory<AccessControl>
 */
class AccessControlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<AccessControl>
     */
    protected $model = AccessControl::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attribute1' => $this->faker->word,
            'attribute2' => $this->faker->word,
        ];
    }
}
