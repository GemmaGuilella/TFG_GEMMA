<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return mixed
     */
    public function view(User $user, Car $car)
    {
        return $car->user()->is($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return mixed
     */
    public function update(User $user, Car $car)
    {
        return $car->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return mixed
     */
    public function delete(User $user, Car $car)
    {
        return $car->user()->is($user);
    }

    /**
     * Determine whether the user is the owner of the car.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return mixed
     */
    public function qr(User $user, Car $car)
    {
        return $car->user()->is($user);
    }

    /**
     * Determines if the car can checkout a payment.
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function checkout(User $user, Car $car): bool
    {
        return $car->isParked();
    }
}
