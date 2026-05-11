<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Model\SendNotification as SendNotificationModel;

/**
 * @extends Factory<SendNotificationModel>
 */
class SendNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<SendNotificationModel>
     */
    protected $model = SendNotificationModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'to' => $this->faker->unique()->safeEmail(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'cc' => [$this->faker->safeEmail(), $this->faker->safeEmail()],
            'bcc' => [$this->faker->safeEmail()],
            'attachments' => [],
            'reply_to' => $this->faker->safeEmail(),
            'channel' => 'email',
        ];
    }
}
