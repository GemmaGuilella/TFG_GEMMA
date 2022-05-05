<?php

namespace Database\Factories;

use App\Enums\LogState;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'car_id' => Car::factory(),
            'state' => $this->faker->randomElement(LogState::cases()),
            'price' => fn (array $attributes) => $attributes['state'] == LogState::Exit ? $this->faker->numberBetween(1, 5000) : null,
            'payment_id' => fn (array $attributes) => $attributes['state'] == LogState::Exit ? Str::random(16) : null,
        ];
    }

    public function enter()
    {
        return $this->state([
            'state' => LogState::Enter,
        ]);
    }

    public function exit()
    {
        return $this->state([
            'state' => LogState::Exit,
        ]);
    }
}
