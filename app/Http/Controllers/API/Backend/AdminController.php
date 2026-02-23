<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Models\Account;
use App\Models\AccountTransfer;
use App\Models\PackageCategory;
use App\Models\Package;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;
use DB;

class AdminController extends Controller
{
    public function adminDashboard()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$logo = DB::table('logos')->where('user_id',$userId)->first();
        $userCount = User::orderBy('id','desc')->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Support ticket fetched successfully.',
                'status_code' => 200,
                'logo' => $logo,
                'userCount' => $userCount,
            ], 200);
        }

        return view('backend.dashboard',compact('logo','userCount'));
    }
    
    public function renewPage()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$activePackage = AdminPackage::where('package_by', $userId)->first();

        return view('backend.renewPackage.index',compact('activePackage'));
    }

    //To update package...
    public function saveRenewData(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'package_category_id' => 'required|exists:package_categories,id',
            'package_id' => 'required|exists:packages,id',
        ]);
        
        //To fet userId..
        $userSupId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userSupId;

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
                    'user_id'       => $userSupId,
                    'package_by'    => $user->id,
                    'assigned_by'   => $userSupId,
                    'package_id'    => $package->id,
                    'start_date'    => $now,
                    'end_date'      => $now->copy()->addDays($package->package_validity),
                    'sms_qty'       => $package->sms_qty,
                    'student_qty'   => $package->student_qty,
                    'status'        => $activePackage->status,
                ]);

                $account = Account::firstOrCreate(
                    ['id' => $userSupId],
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
                    'transfer_by'         => $userSupId,
                    'transfer_type'       => 'Credit',
                    'transfer_amount'     => $package->package_price,
                    'current_amount'      => $account->account_balance,
                    'transfer_date'       => $now,
                    'transfer_purpuse'    => 'Package purchase: ' . $package->package_name,
                    'status'              => true,
                ]);

            } else {
                $newPackage = AdminPackage::create([
                    'user_id'            => $userSupId,
                    'package_by'         => $user->id,
                    'package_id'         => $package->id,
                    'start_date'         => $now,
                    'end_date'           => $now->copy()->addDays($package->package_validity),
                    'sms_remaining'      => $package->sms_qty,
                    'student_remaining'  => $package->student_qty,
                    'status'             => 'active',
                ]);

                AdminPackageHistory::create([
                    'user_id'       => $userSupId,
                    'package_by'    => $newPackage->package_by,
                    'package_id'    => $newPackage->package_id,
                    'start_date'    => $newPackage->start_date,
                    'end_date'      => $newPackage->end_date,
                    'sms_qty'       => $newPackage->sms_remaining,
                    'student_qty'   => $newPackage->student_remaining,
                    'assigned_by'   => $userSupId,
                ]);

                $account = Account::firstOrCreate(
                    ['id' => $userSupId],
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
                    'transfer_by'         => $userSupId,
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
            return redirect()->route('admin.dashboard');

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

    public function adminLogout()
    {
     Auth::guard('web')->logout();
     return Redirect()->route('admin.login');
    }
}
