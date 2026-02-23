<?php

namespace App\Http\Controllers\Backend\UserRole;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Helpers\CurrentUser;
use Auth;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('role-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $userId = CurrentUser::getUserIdFCU();
        if(Auth::user()->role == 'superadmin'){
            $roles = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roles = Role::whereNotIn('name', ['superadmin','admin'])->where('admin_id', $userId)->get();
        }else{
            $userOwnerId = CurrentUser::getOwnerId();
            $userRoleName = Auth::user()->role;
            $roles = Role::whereNotIn('name', ['superadmin','admin',$userRoleName])->where('admin_id', $userOwnerId)->get();
        }
        
        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'roleData'   =>  $roles,
            ], 200);
        }

        return view('backend.userRole.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('role-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $permissions = Permission::get();
        $permissionGroups = User::getpermissionGroups();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'permissionData'   =>  $permissions,
                'permissionGroupData'   =>  $permissionGroups,
            ], 200);
        }

        return view('backend.userRole.roles.create',compact('permissions','permissionGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        //To check user permission...
        if (!auth()->user()->can('role-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $userOwnerId = CurrentUser::getOwnerId();

        $validated = $request->validate([
            'name' => [
                'required',
                \Illuminate\Validation\Rule::unique('roles')->where(function ($q) use ($userOwnerId) {
                    return $q->where('admin_id', $userOwnerId);
                })
            ],
            'permissions' => 'nullable',
        ]);
        
        $roleName = strtolower($request->input('name'));
        $spatieRoleName = $roleName . '_admin' . $userOwnerId;
        if (Role::where('name', $spatieRoleName)->where('guard_name', 'web')->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'A role with this name already exists.',
                    'status_code' => 409,
                ], 409);
            }

            Toastr::error('A role with this name already exists.', 'Error', ["progressbar" => true]);
            return back();
        }


        $role = Role::create(['name' => $spatieRoleName, 'display_name' => $roleName, 'admin_id' =>$userOwnerId, 'guard_name'=> 'web']);
        $role->syncPermissions($request->input('permissions'));

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role created successfully.',
                'status_code' => 200,
            ], 200);
        }

        Toastr::success('Role created Successfully.', 'Success', ["progressbar" => true]);
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('role-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

       $singleRoleData = Role::where('id', $id)->first();

        if(isset($singleRoleData) && $singleRoleData != null){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'Role loaded successfully.',
                    'singleRoleData'   => $singleRoleData,
                    'status_code' => 200,
                ], 200);
            }
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Role not found.',
                    'status_code' => 500,
                ], 500);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('role-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $role = Role::find($id);
        $allPermissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        $permissionGroups = User::getpermissionGroups();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'singleRoleData'   =>  $role,
                'permissionData'   =>  $allPermissions,
                'permissionGroupData'   =>  $permissionGroups,
            ], 200);
        }

        return view('backend.userRole.roles.edit',compact('role','allPermissions','rolePermissions','permissionGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('role-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }
            return redirect()->route('error.accessDenied');
        }

        $userOwnerId = CurrentUser::getOwnerId();
        $role = Role::where('id', $id)->where('admin_id', $userOwnerId)->firstOrFail();

        $validated = $request->validate([
            'name' => [
                'required',
                \Illuminate\Validation\Rule::unique('roles', 'display_name')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('admin_id', $userOwnerId))
            ],
            'permissions' => 'nullable',
        ]);

        $roleName = strtolower($validated['name']);
        $spatieRoleName = $roleName . '_admin' . $userOwnerId;

        if (Role::where('name', $spatieRoleName)->where('guard_name', 'web')->where('id', '!=', $id)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'A role with this name already exists.',
                    'status_code' => 409,
                ], 409);
            }
            Toastr::error('A role with this name already exists.', 'Error');
            return back();
        }

        $role->display_name = $roleName;
        $role->name = $spatieRoleName;

        $role->save();
        $role->syncPermissions($request->input('permissions'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role updated successfully.',
                'status_code' => 200,
            ], 200);
        }

        Toastr::success('Role updated successfully.', 'Success');
        return redirect()->route('roles.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('role-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $role = Role::find($id);
        if (in_array($role->name, ['superadmin','admin'])) {
            Toastr::error('Sorry!! This role you can not be delete.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        if($role->delete()){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Role deleted successfully.',
                    'status_code' => 200,
                ], 200);
            }

            Toastr::success('Role deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('roles.index');
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Something is wrong.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
        
    }
}
