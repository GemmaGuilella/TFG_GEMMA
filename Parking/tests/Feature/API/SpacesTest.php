<?php

namespace Tests\Feature\API;

use App\Models\Car;
use App\Models\User;
use App\Models\Space;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SpacesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // public function test_it_can_associate_car_to_space()
    // {
    //     $user = User::factory()->create();
    //     Sanctum::actingAs($user);
    //     $space = Space::factory()->create(['car_id' => null]);
    //     $car = Car::factory()->create(['user_id' => $user->id]);
    //     $response = $this->putJson(
    //         route(
    //             'space.associate',
    //             [
    //                 'space' => $space,
    //                 'car' => $car
    //             ]
    //         )
    //     );
    //     $response->assertNoContent();
    // }

    // public function test_it_cant_associate_car_to_space()
    // {
    //     $user = User::factory()->create();
    //     Sanctum::actingAs($user);
    //     $car = Car::factory()->create(['user_id' => $user->id]);
    //     $carSpace = Car::factory()->create();
    //     $space = Space::factory()->create(['car_id' => $carSpace->id]);
    //     $response = $this->putJson(
    //         route(
    //             'space.associate',
    //             [
    //                 'space' => $space,
    //                 'car' => $car->id
    //             ]
    //         )
    //     );
    //     $response
    //         ->assertStatus(403);
    // }

    // public function test_it_can_disassociate_car_to_space()
    // {
    //     $user = User::factory()->create();
    //     Sanctum::actingAs($user);
    //     $car = Car::factory()->create(['user_id' => $user->id]);
    //     $space = Space::factory()->create(['car_id' => $car->id]);
    //     $response = $this->putJson(
    //         route(
    //             'space.disassociate',
    //             [
    //                 'space' => $space->id,
    //             ]
    //         )
    //     );
    //     $response->assertNoContent();
    // }

    // public function test_it_cant_disassociate_car_to_space()
    // {
    //     $user = User::factory()->create();
    //     Sanctum::actingAs($user);
    //     $carSpace = Car::factory()->create();
    //     $space = Space::factory()->create(['car_id' => $carSpace->id]);
    //     $response = $this->putJson(
    //         route(
    //             'space.disassociate',
    //             [
    //                 'space' => $space->id,
    //             ]
    //         )
    //     );
    //     $response
    //         ->assertStatus(403);
    // }
}
