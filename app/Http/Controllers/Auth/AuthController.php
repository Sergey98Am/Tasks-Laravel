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
    public function checkToken() {
        try {
            return response()->json([
                'success' => true,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if (!$user) {
                throw new \Exception('Something went wrong');
            }

            $user->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'Check your email',
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
            $user = User::where('email', $request->email)->where('email_verified_at', NULL)->first();

            if ($user) {
                throw new \Exception('Email is not verified');
            }

            $credentials = $request->only('email', 'password');

            if ($request->remember_me) {
                $token = auth()->setTTL(86400 * 30)->attempt($credentials);
            } else {
                $token = JWTAuth::attempt($credentials);
            }

            if (!$token) {
                throw new \Exception('Unauthorized');
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
