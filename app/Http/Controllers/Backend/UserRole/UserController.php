<?php

namespace App\Http\Controllers\Backend\UserRole;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\PackageCategory;
use App\Models\Package;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Models\AccountTransfer;
use App\Models\Account;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Hash;
use Auth;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('user-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fet userId..
        $userOwnerId = CurrentUser::getOwnerId();
        $userId = CurrentUser::getSuperadminId();
        $userRole = Auth::user()->role;
        $userCount = 0;
        $packageCategoryData = PackageCategory::orderBy('id', 'desc')->where('user_id', $userId)->where('status', true)->get();
        $packageData[] = null;

        if (in_array($userRole, ['superadmin'])) {
            $userData = User::whereIn('role', ['admin'])->orderBy('id', 'DESC')->paginate(10);
            $roles = Role::whereIn('name', ['admin'])->get();
            $userCount = User::whereIn('role', ['admin'])->orderBy('id', 'DESC')->count();
        }elseif (in_array($userRole, ['admin'])) {
            $userData = User::where('admin_id', $userOwnerId)->orderBy('id', 'DESC')->paginate(10);
            $roles = Role::whereNotIn('name', ['superadmin','admin'])->where('admin_id', $userOwnerId)->get();
            $userCount = User::where('admin_id', $userOwnerId)->orderBy('id', 'DESC')->count();
        } else {
            $userData = User::where('admin_id', $userOwnerId)->orderBy('id', 'DESC')->paginate(10);
            $roles = Role::whereNotIn('name', ['superadmin','admin',$userRole])->where('admin_id', $userOwnerId)->get();
            $userCount = User::where('admin_id', $userOwnerId)->orderBy('id', 'DESC')->count();
        }


        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'userData'   =>  $userData,
                'userCount'   =>  $userCount,
                'roleData'   =>  $roles,
                'packageCategoryData'   =>  $packageCategoryData,
                'packageData'   =>  $packageData,
            ], 200);
        }

        return view('backend.userRole.users.index', compact('userData','userCount','roles','packageCategoryData','packageData'));
    }

    //To get package...
    public function getPackages(Request $request)
    {
        $data = Package::where('package_category_id', $request->package_category_id)->orderBy('package_name','asc')->where('status', true)->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if (!auth()->user()->can('user-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile'  => 'required|digits:11|unique:users,mobile',
            'password' => 'required|same:password_confirmation',
            'roles' => 'required',
            'package_category_id' => 'nullable',
            'package_id'          => 'nullable|exists:packages,id',
        ]);
        
        //To fet userId..
        $userOwnerId = CurrentUser::getOwnerId();
        $userOwnerIdFCU = CurrentUser::getUserIdFCU();
        $data = $request->all();
        $userRole = Role::where('id', $request->roles)->first();
        $data['role'] = $userRole->name;
        $data['admin_id'] = $userOwnerId;

        DB::beginTransaction();

        // To check role exist or not...
        if (!$userRole) {
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Role not found.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Role not found.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        $data['password'] = Hash::make($data['password']);

        if($user = User::create($data)){
            $user->assignRole($userRole->name);

            if (auth()->user()->role === 'superadmin' && $request->filled('package_id')) {

                $package = Package::findOrFail($request->package_id);
                $now = Carbon::now();

                $newPackage = AdminPackage::create([
                    'user_id'           => $userOwnerId,
                    'package_by'        => $user->id,
                    'package_id'        => $package->id,
                    'start_date'        => $now,
                    'end_date'          => $now->copy()->addDays($package->package_validity),
                    'sms_remaining'     => $package->sms_qty,
                    'student_remaining' => $package->student_qty,
                    'status'            => 'active',
                ]);

                AdminPackageHistory::create([
                    'user_id'       => $userOwnerId,
                    'package_by'    => $newPackage->package_by,
                    'package_id'    => $newPackage->package_id,
                    'start_date'    => $newPackage->start_date,
                    'end_date'      => $newPackage->end_date,
                    'sms_qty'       => $newPackage->sms_remaining,
                    'student_qty'   => $newPackage->student_remaining,
                    'assigned_by'   => $userOwnerIdFCU,
                ]);

                $account = Account::firstOrCreate(
                    ['id' => $userOwnerId],
                    [
                        'account_name' => 'Main Account',
                        'account_holder_name' => 'Admin',
                        'account_number' => '01774444000',
                        'account_balance' => 0,
                        'status' => 1,
                        'user_id' => 1,
                    ]
                );

                $account->increment('account_balance', $package->package_price);
                AccountTransfer::create([
                    'account_id'          => $account->id,
                    'transfer_by'         => $userOwnerIdFCU,
                    'transfer_type'       => 'Credit',
                    'transfer_amount'     => $package->package_price,
                    'current_amount'      => $account->account_balance,
                    'transfer_date'       => $now,
                    'transfer_purpuse'    => 'Package purchase: ' . $package->package_name,
                    'status'              => true,
                ]);
            }

            
            DB::commit();

            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'User created successfully.',
                    'status_code' => 200,
                ], 200);
            }
            
            Toastr::success('User Created Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('user-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

       $singleUserData = User::where('id', $id)->first();
       if(Auth::user()->role == 'superadmin'){
            $roleData = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roleData = Role::whereNotIn('name', ['superadmin','admin'])->get();
        }else{
            $userRoleName = Auth::user()->role;
            $roleData = Role::whereNotIn('name', ['superadmin','admin',$userRoleName])->get();
        }

        if(isset($singleUserData) && $singleUserData != null){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'User loaded successfully.',
                    'singleUserData'   => $singleUserData,
                    'roleData'   =>  $roleData,
                    'status_code' => 200,
                ], 200);
            }
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! User not found.',
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
    public function edit($id)
    {
        //
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
        //To check user permission...
        if (!auth()->user()->can('user-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => ['required', 'regex:/^[0-9]{11}$/', 'unique:users,mobile,'.$id],
            'roles' => 'required'
        ]);

        //To fet userId..
        $userOwnerId = CurrentUser::getOwnerId();
        $data = $request->all();
        $userRole = Role::where('id', $request->roles)->first();
        $data['role'] = $userRole->name;
        $data['admin_id'] = $userOwnerId;

        // To check role exist or not...
        if (!$userRole) {
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Role not found.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Role not found.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
        
        $singleUserData = User::find($id);
        if($singleUserData->update($data)){
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $singleUserData->assignRole($userRole->name);

            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'User updated successfully.',
                    'singleUserData'   =>  $singleUserData,
                    'status_code' => 200,
                ], 200);
            }
            
            Toastr::success('User updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('user-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleUserData = User::findOrFail($id);
        if($singleUserData->delete()){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'User deleted successfully.',
                    'status_code' => 200,
                ], 200);
            }
            
            Toastr::success('User deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
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

    //To active status...
    public function active(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('user-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        User::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('User activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('user-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        User::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('User in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To update package...
    public function packageRenew(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('user-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'package_category_id' => 'required|exists:package_categories,id',
            'package_id' => 'required|exists:packages,id',
        ]);
        
        //To fet userId..
        $userOwnerId = CurrentUser::getOwnerId();
        $userOwnerIdFCU = CurrentUser::getUserIdFCU();
        $data = $request->all();
        $data['user_id'] = $userOwnerId;

        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->admin_id);
            $package = Package::findOrFail($request->package_id);
            $activePackage = AdminPackage::where('package_by', $user->id)->first();
            
            $now = Carbon::now();

            if ($activePackage) {
                $newEndDate = $activePackage->end_date && Carbon::parse($activePackage->end_date)->gt($now)
                    ? Carbon::parse($activePackage->end_date)->addDays($package->package_validity)
                    : $now->copy()->addDays($package->package_validity);
                
                $activePackage->update([
                    'package_by'         => $user->id,
                    'package_id'         => $package->id,
                    'start_date'         => $now,
                    'end_date'           => $newEndDate,
                    'sms_remaining'      => $activePackage->sms_remaining + $package->sms_qty,
                    'student_remaining'  => $package->student_qty,
                    'status'             => 'active',
                ]);

                AdminPackageHistory::create([
                    'user_id'       => $userOwnerId,
                    'package_by'    => $user->id,
                    'assigned_by'   => $userOwnerIdFCU,
                    'package_id'    => $package->id,
                    'start_date'    => $now,
                    'end_date'      => $now->copy()->addDays($package->package_validity),
                    'sms_qty'       => $package->sms_qty,
                    'student_qty'   => $package->student_qty,
                    'status'        => $activePackage->status,
                ]);

                $account = Account::firstOrCreate(
                    ['id' => $userOwnerId],
                    [
                        'account_name' => 'Main Account',
                        'account_holder_name' => 'Admin',
                        'account_number' => '01774444000',
                        'account_balance' => 0,
                        'status' => 1,
                        'user_id' => 1,
                    ]
                );

                $account->increment('account_balance', $package->package_price);
                AccountTransfer::create([
                    'account_id'          => $account->id,
                    'transfer_by'         => $userOwnerIdFCU,
                    'transfer_type'       => 'Credit',
                    'transfer_amount'     => $package->package_price,
                    'current_amount'      => $account->account_balance,
                    'transfer_date'       => $now,
                    'transfer_purpuse'    => 'Package purchase: ' . $package->package_name,
                    'status'              => true,
                ]);

            } else {
                $newPackage = AdminPackage::create([
                    'user_id'            => $userOwnerId,
                    'package_by'         => $user->id,
                    'package_id'         => $package->id,
                    'start_date'         => $now,
                    'end_date'           => $now->copy()->addDays($package->package_validity),
                    'sms_remaining'      => $package->sms_qty,
                    'student_remaining'  => $package->student_qty,
                    'status'             => 'active',
                ]);

                AdminPackageHistory::create([
                    'user_id'       => $userOwnerId,
                    'package_by'    => $newPackage->package_by,
                    'package_id'    => $newPackage->package_id,
                    'start_date'    => $newPackage->start_date,
                    'end_date'      => $newPackage->end_date,
                    'sms_qty'       => $newPackage->sms_remaining,
                    'student_qty'   => $newPackage->student_remaining,
                    'assigned_by'   => $userOwnerIdFCU,
                ]);

                $account = Account::firstOrCreate(
                    ['id' => $userOwnerId],
                    [
                        'account_name' => 'Main Account',
                        'account_holder_name' => 'Admin',
                        'account_number' => '01774444000',
                        'account_balance' => 0,
                        'status' => 1,
                        'user_id' => 1,
                    ]
                );

                $account->increment('account_balance', $package->package_price);
                AccountTransfer::create([
                    'account_id'          => $account->id,
                    'transfer_by'         => $userOwnerIdFCU,
                    'transfer_type'       => 'Credit',
                    'transfer_amount'     => $package->package_price,
                    'current_amount'      => $account->account_balance,
                    'transfer_date'       => $now,
                    'transfer_purpuse'    => 'Package purchase: ' . $package->package_name,
                    'status'              => true,
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Package renewed successfully!',
                    'status_code' => 200
                ]);
            }

            Toastr::success('Package renewed successfully!', 'Success', ["progressbar" => true]);
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong: '.$e->getMessage(),
                    'status_code' => 500
                ]);
            }

            Toastr::error('Something went wrong!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

}
