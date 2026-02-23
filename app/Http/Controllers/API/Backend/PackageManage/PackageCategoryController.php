<?php

namespace App\Http\Controllers\Backend\PackageManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\PackageCategory;
use App\Helpers\CurrentUser;

class PackageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('package-category-list', 'web')) {
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
        $packageCategoryData = PackageCategory::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allPackageCategoryCount = PackageCategory::orderBy('id', 'desc')->where('user_id', $userId)->count();
        
        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status_code' => 200,
                'message' => ' Package categories fetched successfully.',
                'packageCategoryData' => $packageCategoryData,
                'allPackageCategoryCount' => $allPackageCategoryCount,
            ], 200);
        }

        return view('backend.subscription.category.index',compact('packageCategoryData','allPackageCategoryCount'));
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
        if (!auth()->user()->can('package-category-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'category_name'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(PackageCategory::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => ' Package category created successfully.',
                    'status_code' => 200,
                    'packageCategoryData' => $data
                ], 200);
            }
            
            Toastr::success(' Package category created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('package-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singlePackageCategoryData = PackageCategory::where('id', $id)->first();
        if(isset($singlePackageCategoryData) && $singlePackageCategoryData != null){
            return response()->json([
                'message'   =>  ' Package category loaded successfully.',
                'status_code'   => 200,
                'singlePackageCategoryData'   => $singlePackageCategoryData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry,  Package category not found.!',
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
        if (!auth()->user()->can('package-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'category_name'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singlePackageCategoryData = PackageCategory::where('id', $id)->first();

        if($singlePackageCategoryData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => ' Package category updated successfully.',
                    'status_code' => 200,
                    'singlePackageCategoryData' => $singlePackageCategoryData
                ], 200);
            }

            Toastr::success(' Package category updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('package-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singlePackageCategoryData = PackageCategory::where('id', $id)->first();
        if($singlePackageCategoryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => ' Package category deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success(' Package category deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('package-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        PackageCategory::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => ' Package category activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success(' Package category activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('package-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        PackageCategory::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => ' Package category in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success(' Package category in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
