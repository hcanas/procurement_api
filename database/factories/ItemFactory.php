<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'title' => $this->faker->words(rand(1, 3), true),
            'category_id' => rand(1, 50),
            'commodity_type' => $this->faker->word(),
            'details' => $this->faker->words(rand(5, 20), true),
        ];
    }
}
