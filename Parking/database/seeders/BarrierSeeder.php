<?php

namespace Database\Seeders;

use App\Models\Barrier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Barrier::factory()
            ->count(2)
            ->create();
    }
}
