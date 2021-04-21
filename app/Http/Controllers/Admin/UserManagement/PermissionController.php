<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use JWTAuth;

class PermissionController extends Controller
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
            if (Gate::denies('permission_access')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $permissions = Permission::orderBy('id', 'DESC')->get();

            return response()->json([
                'permissions' => $permissions
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
    public function store(CreatePermissionRequest $request)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('permission_create')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $permission = Permission::create([
                'title' => $request->title,
            ]);

            if (!$permission) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'permission' => $permission,
                'message' => 'Permission successfully created'
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
     * @param $permissionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermissionRequest $request, $permissionId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('permission_edit')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $permission = Permission::find($permissionId);

            if (!$permission) {
                throw new \Exception('Permission does not exist');
            }

            $permission->update([
                'title' => $request->title,
            ]);

            return response()->json([
                'permission' => $permission,
                'message' => 'Permission successfully updated'
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
     * @param $permissionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($permissionId)
    {
        try {
            $error_status_code = 400;
            if (Gate::denies('permission_delete')) {
                $error_status_code = 403;
                throw new \Exception('Forbidden 403');
            }

            $permission = Permission::find($permissionId);

            if (!$permission) {
                throw new \Exception('Permission does not exist');
            }

            $permission->delete();

            return response()->json([
                'permission' => $permission,
                'message' => 'Permission successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $error_status_code);
        }
    }
}
