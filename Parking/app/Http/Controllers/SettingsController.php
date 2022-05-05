<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use App\Http\Requests\Settings\IndexRequest;
use App\Http\Requests\Settings\UpdateRequest;
use App\Http\Resources\SettingsResource;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(Admin::class)->only('update');
    }

    public function index(IndexRequest $request)
    {
        return new SettingsResource(Settings::first());
    }

    public function update(UpdateRequest $request)
    {
        return new SettingsResource(
            tap(Settings::first())
                ->update($request->validated()),
        );
    }
}
