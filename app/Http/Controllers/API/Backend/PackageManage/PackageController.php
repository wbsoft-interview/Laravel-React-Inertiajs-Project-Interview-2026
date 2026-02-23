<?php

namespace App\Http\Controllers\Backend\PackageManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('package-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getSuperadminId();
        $packageData = Package::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allPackageCount = Package::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $packageCategoryData = PackageCategory::orderBy('id', 'desc')->where('user_id', $userId)->where('status', true)->get();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Package fetched successfully.',
                'packageData' => $packageData,
                'allPackageCount' => $allPackageCount,
                'packageCategoryData' => $packageCategoryData,
            ], 200);
        }

        return view('backend.subscription.package.index',compact('packageData','allPackageCount','packageCategoryData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        if (!auth()->user()->can('package-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'package_category_id'=> 'required',
            'package_name'=> 'required',
            'package_price'=> 'required',
            'package_validity'=> 'required',
            'sms_qty'=> 'required',
            'student_qty'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Package::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Package created successfully.',
                    'status_code' => 200,
                    'accountData' => $data
                ], 200);
            }
            
            Toastr::success('Package created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }
            
            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
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
        if (!auth()->user()->can('package-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singlePackageData = Package::where('id', $id)->first();
        if(isset($singlePackageData) && $singlePackageData != null){
            return response()->json([
                'message'   =>  'Package loaded successfully.',
                'status_code'   => 200,
                'singlePackageData'   => $singlePackageData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Package not found.!',
                'status_code'   => 500
            ], 500);
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
        if (!auth()->user()->can('package-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'package_category_id'=> 'required',
            'package_name'=> 'required',
            'package_price'=> 'required',
            'package_validity'=> 'required',
            'sms_qty'=> 'required',
            'student_qty'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singlePackageData = Package::where('id', $id)->first();

        if($singlePackageData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Package updated successfully.',
                    'status_code' => 200,
                    'singlePackageData' => $singlePackageData
                ], 200);
            }

            Toastr::success('Package updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
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
        if (!auth()->user()->can('package-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singlePackageData = Package::where('id', $id)->first();
        if($singlePackageData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Package deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Package deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To active status...
    public function active(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('package-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Package::where('id', $id)->update(['status' => true]);
        Package::whereNotIn('id', [$id])->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Package activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Package activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('package-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Package::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Package in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Package in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To get purchase account list...
    public function purchaseAccountList(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('package-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $adminPackageData = AdminPackage::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allAdminPackageCount = AdminPackage::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Purchase package fetched successfully.',
                'adminPackageData' => $adminPackageData,
                'allAdminPackageCount' => $allAdminPackageCount,
            ], 200);
        }

        return view('backend.subscription.purchasePackage.index',compact('adminPackageData','allAdminPackageCount'));
    }
    
    //To get purchase account details...
    public function purchaseAccountProfile(Request $request, $adminPackageId)
    {
        //To check user permission...
        if (!auth()->user()->can('package-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $singleAdminPackageData = AdminPackage::where('id', $adminPackageId)->first();
        // dd($singleAdminPackageData);
        $adminPackageHistoryData = AdminPackageHistory::orderBy('id', 'desc')->where('user_id', $userId)->where('package_by', $singleAdminPackageData->package_by)->paginate(10);
        $allAdminPackageHistoryCount = AdminPackageHistory::orderBy('id', 'desc')->where('user_id', $userId)->where('package_by', $singleAdminPackageData->package_by)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Purchase package fetched successfully.',
                'singleAdminPackageData' => $singleAdminPackageData,
                'adminPackageHistoryData' => $adminPackageHistoryData,
                'allAdminPackageHistoryCount' => $allAdminPackageHistoryCount,
            ], 200);
        }

        return view('backend.subscription.purchasePackage.details',compact('singleAdminPackageData','adminPackageHistoryData','allAdminPackageHistoryCount'));
    }
}
