<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Requests\Cars\ShowRequest;
use App\Http\Requests\Cars\IndexRequest;
use App\Http\Requests\Cars\StoreRequest;
use App\Http\Requests\Cars\UpdateRequest;
use App\Http\Requests\Cars\DestroyRequest;

class CarController extends Controller
{
    /**
     * Creates a new class instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        return CarResource::collection($request->user()->cars);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return new CarResource(
            $request
                ->user()
                ->cars()
                ->create($request->validated()),
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car $car
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest  $request, Car $car)
    {
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Car $car)
    {
        return new CarResource(
            tap($car)
                ->update($request->validated()),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Car $car)
    {
        $car->delete();
        return response()->noContent();
    }
}
