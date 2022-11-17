<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundSource>
 */
class FundSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(rand(1, 3), true),
            'amount' => rand(1, 1000000),
            'year' => rand(2022, 2024),
            'office_id' => rand(1, 39),
            'last_modified_by_id' => rand(1, 100),
        ];
    }
}
