<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RemoveItemRequest>
 */
class RemoveItemRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $item = Item::factory()->create();

        return [
            'user_id' => mt_rand(1, 6),
            'item_id' => $item->id,
            'status' => $this->faker->randomElement(['STORED', 'AUCTIONED']),
            'unit_confirmed' => $this->faker->boolean,
        ];
    }
}
