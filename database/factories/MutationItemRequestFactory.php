<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MutationItemRequest>
 */
class MutationItemRequestFactory extends Factory
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
            'item_id' => $item->id,
            'from_user_id' => mt_rand(1, 6),
            'to_user_id' => mt_rand(1, 6),
            'unit_confirmed' => $this->faker->boolean,
            'recipient_confirmed' => $this->faker->boolean,
        ];
    }
}
