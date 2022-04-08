<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQRRequest;
use App\Http\Resources\GenerateQRResource;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class GenerateQR extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GenerateQRRequest $request, Car $car)
    {
        $car->update(['token' => Str::random(16), 'token_created_at' => now()]);
        return new GenerateQRResource($car);
    }
}
