<?php

namespace App\Http\Controllers\Backend\IncomeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\IncomeCategory;
use App\Models\Income;
use App\Helpers\CurrentUser;

class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-category-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $incomeCategoryData = IncomeCategory::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allIncomeCategoryCount = IncomeCategory::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income category fetched successfully.',
                'status_code' => 200,
                'incomeCategoryData' => $incomeCategoryData,
                'allIncomeCategoryCount' => $allIncomeCategoryCount,
            ], 200);
        }

        return view('backend.incomeManage.category.index',compact('incomeCategoryData','allIncomeCategoryCount'));
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
        if (!auth()->user()->can('income-category-create', 'web')) {
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

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(IncomeCategory::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income category created successfully.',
                    'status_code' => 200,
                    'incomeCategoryData' => $data
                ], 200);
            }

            Toastr::success('Income category created successfully.', 'Success', ["progressbar" => true]);
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

    public function show(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleIncomeCategoryData = IncomeCategory::where('id', $id)->first();
        if(isset($singleIncomeCategoryData) && $singleIncomeCategoryData != null){
            return response()->json([
                'message'   =>  'Income category loaded successfully.',
                'status_code'   => 200,
                'singleIncomeCategoryData'   => $singleIncomeCategoryData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Income category not found.!',
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
        if (!auth()->user()->can('income-category-edit', 'web')) {
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

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleIncomeCategoryData = IncomeCategory::where('id', $id)->first();

        if($singleIncomeCategoryData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income category updated successfully.',
                    'status_code' => 200,
                    'singleIncomeCategoryData' => $singleIncomeCategoryData
                ], 200);
            }

            Toastr::success('Income category updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('income-category-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleIncomeCategoryData = IncomeCategory::where('id', $id)->first();
        if($singleIncomeCategoryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income category deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Income category deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('income-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        IncomeCategory::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income category activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Income category activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        IncomeCategory::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income category in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Income category in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
