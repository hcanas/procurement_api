<?php

namespace Database\Seeders;

use App\Models\Wfp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WfpSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wfp::factory()
            ->count(500)
            ->create();
    }
}
