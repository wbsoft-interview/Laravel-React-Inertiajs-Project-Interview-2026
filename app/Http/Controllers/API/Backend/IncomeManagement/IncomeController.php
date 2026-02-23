<?php

namespace App\Http\Controllers\Backend\IncomeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\IncomeCategory;
use App\Models\Income;
use App\Helpers\CurrentUser;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-list', 'web')) {
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
        $incomeData = Income::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allIncomeCount = Income::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income fetched successfully.',
                'status_code' => 200,
                'incomeData' => $incomeData,
                'allIncomeCount' => $allIncomeCount,
                'incomeCategoryData' => $incomeCategoryData,
            ], 200);
        }

        return view('backend.incomeManage.income.index',compact('incomeData','allIncomeCount','incomeCategoryData'));
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
        if (!auth()->user()->can('income-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'income_category_id'=> 'required',
            'income_name'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Income::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income created successfully.',
                    'status_code' => 200,
                    'incomeData' => $data
                ], 200);
            }

            Toastr::success('Income created successfully.', 'Success', ["progressbar" => true]);
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

    public function update(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'income_category_id'=> 'required',
            'income_name'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleIncomeData = Income::where('id', $id)->first();

        if($singleIncomeData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income updated successfully.',
                    'status_code' => 200,
                    'singleIncomeData' => $singleIncomeData
                ], 200);
            }

            Toastr::success('Income updated successfully.', 'Success', ["progressbar" => true]);
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

    public function destroy(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleIncomeData = Income::where('id', $id)->first();
        if($singleIncomeData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Income deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('income-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Income::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Income activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Income::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Income in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
