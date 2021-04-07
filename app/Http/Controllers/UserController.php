<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use JWTAuth;

class UserController extends Controller
{
    public function changeDetails(UserRequest $request)
    {
        try {
            $userId = JWTAuth::user()->id;
            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User does not exist');
            }

            if (!$request->filled('password')) {
                $user->update([
                    'name' => $request->name,
                ]);

                return response()->json([
                    'message' => 'Profile updated successfully!',
                    'user' => $user,
                ], 200);
            }

            $user->password = bcrypt($request->password);
            $user->update([
                'name' => $request->name,
                'password' => $user->password,
            ]);

            return response()->json([
                'message' => 'Profile (and password) updated successfully!',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
