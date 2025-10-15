<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceItemRequest>
 */
class MaintenanceItemRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        return [
            'user_id' => mt_rand(1, 6),
            'item_id' => $item->id,
            'item_status' => $this->faker->randomElement(['GOOD', 'DAMAGED', 'REPAIRED']),
            'information' => $this->faker->sentence,
            'request_status' => $this->faker->randomElement(['PENDING', 'PROCESS', 'COMPLETED', 'REJECTED', 'REMOVED']),
            'unit_confirmed' => $this->faker->boolean,
        ];
    }
}
