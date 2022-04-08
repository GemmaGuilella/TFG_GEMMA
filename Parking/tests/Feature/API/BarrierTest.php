<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Car;
use App\Models\Barrier;
use Illuminate\Support\Facades\Crypt;

class BarrierTest extends TestCase
{
    public function test_it_can_be_updated()
    {
        $barrier = Barrier::factory()->closed()->create();
        $response = $this->putJson(route('barriers.update', $barrier), [
            'state' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'state',
                    'ip',
                ]
            ]);
    }

    public function test_it_can_be_updated2()
    {
        $barrier = Barrier::factory()->closed()->create();
        $response = $this->putJson(route('barriers.update', $barrier), [
            'state' => true,
            'ip' => $ip = '1.1.1.1',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'ip' => $ip,
            ])
            ->assertJsonStructure([
                'data' => [
                    'state',
                    'ip',
                ]
            ]);
    }
    public function test_it_can_be_open()
    {
        $car = Car::factory()->create();
        $barrier = Barrier::factory()->closed()->create();
        $response = $this->postJson(route('barriers.open'), [
            'qr' => Crypt::encryptString($car->id),
        ]);

        // Falta retorna la ID de la plaça assignada
        // Falta obrir la barrera xd
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'state',
                    'ip',
                ]
            ]);
    }
}
