<?php

namespace Tests\Feature\API;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CarsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_can_show_all_cars()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Car::factory()->for($user)->count(2)->create();

        $response = $this->getJson(route('cars.index'));
        // DD($response);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'matricula',
                    ]
                ]
            ]);
    }

    public function test_it_can_create_cars()
    {
        $matricula = (new \Faker\Factory())::create();
        $matricula->addProvider(new \Faker\Provider\Fakecar($matricula));
        $matricula = $matricula->vehicleRegistration('[0-9]{4} [A-Z]{3}');

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson(route('cars.store'), [
            'matricula' => $matricula,
        ]);
        $this->assertEquals($user->cars()->count(), 1);

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'matricula' => $matricula,
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'matricula',
                ]
            ]);

        $response = $this->postJson(route('cars.store'), [
            'matricula' => $matricula,
        ]);

        $this->assertEquals($user->cars()->count(), 1);

        $response->assertJsonValidationErrors('matricula');
    }

    public function test_it_fails_to_create_car_with_same_matricula()
    {
        $matricula = (new \Faker\Factory())::create();
        $matricula->addProvider(new \Faker\Provider\Fakecar($matricula));
        $matricula = $matricula->vehicleRegistration('[0-9]{4} [A-Z]{3}');

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson(route('cars.store'), [
            'matricula' => $matricula,
        ]);
        $this->assertEquals($user->cars()->count(), 1);

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'matricula' => $matricula,
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'matricula',
                ]
            ]);
    }

    public function test_it_can_show_cars()
    {
        $car = Car::factory()->create();

        Sanctum::actingAs($car->user);
        $response = $this->getJson(route('cars.show', $car));

        $response
            ->assertOk()
            ->assertJsonFragment([
                'matricula' => $car->matricula,
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'matricula',
                ]
            ]);
    }

    public function test_it_can_update_cars()
    {
        $matricula = (new \Faker\Factory())::create();
        $matricula->addProvider(new \Faker\Provider\Fakecar($matricula));
        $matricula = $matricula->vehicleRegistration('[0-9]{4} [A-Z]{3}');
        $car = Car::factory()->create([
            'matricula' => $matricula,
        ]);
        Sanctum::actingAs($car->user);
        $response = $this->putJson(route('cars.update', $car), [
            'matricula' => $matricula,
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'matricula' => $matricula,
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'matricula',
                ]
            ]);
    }

    public function test_it_can_delete_cars()
    {
        $car = Car::factory()->create();

        Sanctum::actingAs($car->user);
        $response = $this->deleteJson(route('cars.destroy', $car));

        $response->assertNoContent();

        $this->assertModelMissing($car);
    }
}