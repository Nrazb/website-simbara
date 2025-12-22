<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = Type::factory()->create();

        $code = mt_rand(1,1000);
        $order_number = mt_rand(1,1000);

        return [
            'id' => $code . "-" . $order_number,
            'user_id' => mt_rand(1, 6),
            'type_id' => $type->id,
            'code' => $code,
            'order_number' => $order_number,
            'name' => $this->faker->word,
            'cost' => $this->faker->numberBetween(1000, 100000),
            'acquisition_date' => $this->faker->date,
            'acquisition_year' => $this->faker->year,
            'status' => $this->faker->randomElement(['AVAILABLE', 'BORROWED']),
        ];
    }
}
