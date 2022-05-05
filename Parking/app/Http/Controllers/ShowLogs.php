<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;

class ShowLogs extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(Admin::class);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return LogResource::collection(
            Log::all(),
        );
    }
}
