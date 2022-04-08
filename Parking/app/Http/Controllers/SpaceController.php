<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Http\Requests\Spaces\UpdateRequest;
use App\Http\Requests\Spaces\DisassociateRequest;
use App\Http\Resources\SpaceResource;
use App\Models\Car;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function associate(UpdateRequest $request, Space $space, Car $car)
    {
        $space->car()->associate($car);
        return response()->noContent();
    }

    public function disassociate(DisassociateRequest $request, Space $space, Car $car)
    {
        $space->car()->disassociate($space);
        return response()->noContent();
    }
}
