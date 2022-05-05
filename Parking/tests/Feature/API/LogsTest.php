<?php

namespace Tests\Feature\API;

use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_logs()
    {
        $user = User::factory()->admin(true)->create();
        Sanctum::actingAs($user);
        Log::factory()->count(5)->create();
        $response = $this->getJson(route('logs.index'));

        $response
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'state',
                        'car',
                        'price',
                        'payment_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_cant_get_logs()
    {
        $user = User::factory()->admin(false)->create();
        Sanctum::actingAs($user);
        Log::factory()->count(5)->create();
        $response = $this->getJson(route('logs.index'));

        $response
            ->assertForbidden();
    }
}
