<?php

namespace Database\Seeders;

use App\Models\Space;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Space::factory()
            ->count(26)
            ->freed()
            ->create();
    }
}
