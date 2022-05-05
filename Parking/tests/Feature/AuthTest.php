<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_register_users()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password = 'Password123!',
            'password_confirmation' => $password,
            'dni' => Str::random(9),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'device_name' => 'web',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure(['token', 'user']);
    }

    /** @test */
    public function it_can_login_users()
    {
        $user = User::factory()->create([
            'password' => $password = 'Password123!',
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'web',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['token', 'user']);
    }

    /** @test */
    public function it_can_get_users()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('auth.user'));

        $response
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonPath('data.name', $user->name)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.phone', $user->phone)
            ->assertJsonPath('data.dni', $user->dni);
    }

    /** @test */
    public function it_can_logout_users()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.logout'), [
            'device_name' => 'web',
        ]);

        $response->assertNoContent();
    }

    /** @test */
    public function it_can_edit_users()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $name = 'Test 1';
        $password = $user->password;
        $dni = '39393939A';
        $phone = '+34600606060';

        $response = $this->putJson(route('auth.user.edit'), [
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password,
            'dni' => $dni,
            'phone' => $phone,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonPath('data.name', $name)
            ->assertJsonPath('data.phone', $phone)
            ->assertJsonPath('data.dni', $dni);
    }
}
