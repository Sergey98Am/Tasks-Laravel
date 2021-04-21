<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('user_access')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $users = User::with('role')->orderBy('id', 'DESC')->get();
            $roles = Role::all();

            return response()->json([
                'users' => $users,
                'roles' => $roles,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('user_create')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            if (!$user) {
                throw new \Exception('Something went wrong');
            }

            $user->sendEmailVerificationNotification();
            Password::sendResetLink($request->only('email'));

            return response()->json([
                'user' => $user->load('role'),
                'message' => 'User successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('user_edit')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User does not exist');
            }

            $user->update([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            return response()->json([
                'user' => $user->load('role'),
                'message' => 'User successfully updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($userId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('user_delete')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User does not exist');
            }

            $user->delete();

            return response()->json([
                'user' => $user,
                'message' => 'User successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }
}
