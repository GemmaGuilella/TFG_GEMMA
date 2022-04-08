<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use App\Models\Space;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    public function associate(User $user, Space $space, Car $car)
    {
        return $car->user()->is($user) && $space->isAvailable();
    }

    public function disassociate(User $user, Space $space)
    {
        return !$space->isAvailable() && $space->car->user()->is($user);
    }
}
