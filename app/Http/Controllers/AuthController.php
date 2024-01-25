<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *      name="name",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *      name="email",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *      name="password",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Registration successfull"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
            ]
        ];
        $data = $request->validate($rules);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ];

        $user = User::create($userData);
        Auth::loginUsingId($user->id);
        $token = $user->createToken('todo_user')->plainTextToken;
        $user->token = $token;
        return response()->json([
            'name' => $user->name,
            'token' => $user->token,
            'email' => $user->email,
        ], 200);
    }

    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and generate token",
     *     tags={"Users"},
     *     @OA\Parameter(
     *      name="email",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *      name="password",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Registration successfull"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function login(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 10)) {
            return response([
                'error' => __('auth.login_limit_exceeded')
            ]);
        }
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => [
                'required',
                Password::min(6)
            ]
        ]);

        if (!Auth::attempt($credentials)) {
            RateLimiter::hit($this->throttleKey(), 3600);
            return response([
                'error' => __('Неверные учетные данные')
            ]);
        }
        RateLimiter::clear($this->throttleKey());

        $user = Auth::user();
        Auth::login($user);
        $token = $user->createToken('todo_user')->plainTextToken;
        $user->token = $token;
        return response()->json([
            'name' => $user->name,
            'token' => $user->token,
            'email' => $user->email,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Revoque the authenticated user",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="User revoqued successfully")
     * )
     */
    function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response(['success' => true], 200);
    }
}
