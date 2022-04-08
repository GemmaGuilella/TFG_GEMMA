<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Space::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'car_id' => Car::factory()
        ];
    }

    public function freed()
    {
        return $this->state(function () {
            return [
                'car_id' => null,
            ];
        });
    }
}
