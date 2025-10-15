<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemRequest>
 */
class ItemRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = Type::factory()->create();

        return [
            'user_id' => mt_rand(1, 6),
            'type_id' => $type->id,
            'name' => $this->faker->word,
            'detail' => $this->faker->sentence,
            'qty' => $this->faker->numberBetween(1, 10),
            'reason' => $this->faker->sentence,
        ];
    }
}
