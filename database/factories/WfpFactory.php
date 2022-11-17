<?php

namespace Database\Factories;

use App\Models\FundSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wfp>
 */
class WfpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fund_source = FundSource::query()->inRandomOrder()->first();
        
        return [
            'code' => date('Ymd').'-'.bin2hex(random_bytes(2)),
            'function_type' => ['Support', 'Core', 'Strategy'][rand(0, 2)],
            'deliverables' => $this->faker->words(rand(10, 20), true),
            'activities' => $this->faker->words(rand(10, 20), true),
            'timeframe_from' => $fund_source->year.'-'.rand(1, 6).'-'.rand(1, 28),
            'timeframe_to' => $fund_source->year.'-'.rand(6, 12).'-'.rand(1, 28),
            'target_q1' => rand(1, 15),
            'target_q2' => rand(1, 15),
            'target_q3' => rand(1, 15),
            'target_q4' => rand(1, 15),
            'item' => $this->faker->words(rand(10, 20), true),
            'cost' => rand(1000, 200000),
            'fund_source_id' => $fund_source->id,
            'responsible_person_id' => rand(1, 100),
            'status' => 'for eval:l1',
            'last_modified_by_id' => rand(1, 100),
        ];
    }
}
