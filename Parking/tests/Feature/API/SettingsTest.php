<?php

namespace Tests\Feature\API;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_settings()
    {

        $user = User::factory()->admin()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('settings.update'), [
            'price_hour' => $priceHour = 800,
            'token_expiration' => $tokenExpiration = 5
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'price_hour' => $priceHour,
                'token_expiration' => $tokenExpiration,
            ])->assertJsonStructure([
                'data' => [
                    'price_hour',
                    'token_expiration'
                ]
            ]);

        $settings = Settings::first();
        $this->assertEquals($settings->price_hour, $priceHour);
        $this->assertEquals($settings->token_expiration, $tokenExpiration);
    }

    /** @test */
    public function it_can_not_update_settings()
    {

        $user = User::factory()->admin(false)->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('settings.update'), [
            'price_hour' => $priceHour = 800,
            'token_expiration' => $tokenExpiration = 5
        ]);

        $response
            ->assertForbidden();

        $settings = Settings::first();
        $this->assertNotEquals($settings->price_hour, $priceHour);
        $this->assertNotEquals($settings->token_expiration, $tokenExpiration);
    }

    /** @test */
    public function it_can_read_settings()
    {

        $user = User::factory()->admin(false)->create();
        Sanctum::actingAs($user);
        $settings = Settings::first();

        $response = $this->getJson(route('settings.index'));

        $response
            ->assertOk()
            ->assertJsonFragment([
                'price_hour' => $settings->price_hour,
                'token_expiration' => $settings->token_expiration,
            ])->assertJsonStructure([
                'data' => [
                    'price_hour',
                    'token_expiration'
                ]
            ]);
    }
}
