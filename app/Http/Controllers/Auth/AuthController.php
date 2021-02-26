<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            if (!$user) {
                throw new \Exception('Something went wrong');
            }

            if ($request->rememberMe) {
                $token = auth()->setTTL(86400 * 30)->fromUser($user);
            }

            return response()->json([
                'token' => $token,
                'user' => User::find($user->id),
                'ttl' => JWTAuth::factory()->getTTL(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                throw new \Exception('Unauthorized');
            }

            if ($request->remember_me) {
                $token = auth()->setTTL(86400 * 30)->attempt($credentials);
            }

            return response()->json([
                'token' => $token,
                'user' => User::find(JWTAuth::user()->id)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(JWTAuth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate();

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
