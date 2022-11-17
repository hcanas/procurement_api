<?php

namespace Database\Seeders;

use App\Models\FundSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSourceSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FundSource::factory()
            ->count(200)
            ->create();
    }
}
