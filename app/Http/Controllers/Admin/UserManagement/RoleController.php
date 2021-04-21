<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use JWTAuth;

class RoleController extends Controller
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
            if (Gate::denies('role_access')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $roles = Role::with('permissions')->orderBy('id', 'DESC')->get();
            $permissions = Permission::all();

            return response()->json([
                'roles' => $roles,
                'permissions' => $permissions,
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
    public function store(CreateRoleRequest $request)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('role_create')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $role = Role::create([
                'title' => $request->title,
            ]);

            $role->permissions()->attach($request->permissions);

            if (!$role) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'role' => $role->load('permissions'),
                'message' => 'Role successfully created'
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
     * @param $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, $roleId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('role_edit')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $role = Role::find($roleId);

            if (!$role) {
                throw new \Exception('Role does not exist');
            }

            $role->update([
                'title' => $request->title,
            ]);

            $role->permissions()->sync($request->permissions);

            return response()->json([
                'role' => $role->load('permissions'),
                'message' => 'Role successfully updated'
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
     * @param $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($roleId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('role_delete')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $role = Role::find($roleId);

            if (!$role) {
                throw new \Exception('Role does not exist');
            }

            $role->delete();

            return response()->json([
                'role' => $role,
                'message' => 'Role successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }

    public function abilities()
    {
        try {
            $permissions = JWTAuth::user()->role()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('title')->toArray();

            return response()->json([
                'permissions' => $permissions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
