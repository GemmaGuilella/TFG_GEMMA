<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Creates a new class instance.
     */
    public function __construct()
    {
        $this->middleware('guest:sanctum')->only(['login', 'register']);
        $this->middleware('auth:sanctum')->only(['user', 'saveUser', 'logout']);
    }

    /**
     * Register the user.
     *
     * @param RegisterRequest $request
     *
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => new UserResource($user),
        ], 201);
    }


    /**
     * Logs the user in.
     *
     * @param LoginRequest $request
     *
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 200);
    }

    /**
     * Returns the authenticated user.
     *
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Returns the authenticated user.
     *
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function saveUser(UpdateUserRequest $request): UserResource
    {
        return new UserResource(
            tap($request->user())
                ->update($request->validated()),
        );
    }

    /**
     * Returns the authenticated user.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(LogoutRequest $request)
    {
        auth()->user()->tokens()->where('name', $request->device_name)->delete();

        return response()->noContent();
    }
}
