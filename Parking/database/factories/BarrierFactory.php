<?php

namespace Database\Factories;

use App\Models\Barrier;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarrierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Barrier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'state' => $this->faker->boolean(),
            'token' => Str::random(16),
            'ip' => $this->faker->ipv4(),
        ];
    }

    /**
     * Sets the barrier open.
     *
     * @return static
     */
    public function open(): static
    {
        return $this->state([
            'state' => true,
        ]);
    }

    /**
     * Sets the barrier as closed.
     *
     * @return static
     */
    public function closed(): static
    {
        return $this->state([
            'state' => false,
        ]);
    }

    /**
     * Sets the barrier's token.
     *
     * @param string $token
     * @return static
     */
    public function token(string $token): static
    {
        return $this->state([
            'token' => $token,
        ]);
    }

    /**
     * Sets the barrier's IP.
     *
     * @param string $ip
     * @return static
     */
    public function ip(string $ip): static
    {
        return $this->state([
            'ip' => $ip,
        ]);
    }
}
