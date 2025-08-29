<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DunningReminder>
 */
class DunningReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sentAt = fake()->dateTimeBetween('-2 months', 'now');
        
        return [
            'invoice_id' => Invoice::factory(),
            'type' => fake()->randomElement(['email', 'sms', 'both']),
            'reminder_level' => fake()->numberBetween(1, 3),
            'sent_at' => $sentAt,
            'status' => fake()->randomElement(['sent', 'delivered', 'failed']),
            'message_content' => fake()->paragraph(),
            'delivery_response' => [
                'message_id' => fake()->uuid(),
                'status' => 'delivered',
                'timestamp' => $sentAt->format('Y-m-d H:i:s'),
            ],
            'next_reminder_at' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
        ];
    }

    /**
     * Indicate that the reminder was delivered successfully.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'delivery_response' => [
                'message_id' => fake()->uuid(),
                'status' => 'delivered',
                'timestamp' => $attributes['sent_at']->format('Y-m-d H:i:s'),
                'delivery_time' => fake()->numberBetween(1, 30) . ' seconds',
            ],
        ]);
    }

    /**
     * Indicate that the reminder failed to send.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'delivery_response' => [
                'error_code' => fake()->randomElement(['INVALID_NUMBER', 'INSUFFICIENT_CREDIT', 'NETWORK_ERROR']),
                'error_message' => fake()->sentence(),
                'timestamp' => $attributes['sent_at']->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Indicate that this is a first reminder.
     */
    public function firstReminder(): static
    {
        return $this->state(fn (array $attributes) => [
            'reminder_level' => 1,
            'message_content' => 'This is a friendly reminder that your invoice is past due. Please make payment at your earliest convenience.',
        ]);
    }

    /**
     * Indicate that this is a final notice.
     */
    public function finalNotice(): static
    {
        return $this->state(fn (array $attributes) => [
            'reminder_level' => 3,
            'message_content' => 'FINAL NOTICE: Your invoice is significantly overdue. Please contact us immediately to avoid further action.',
        ]);
    }
}