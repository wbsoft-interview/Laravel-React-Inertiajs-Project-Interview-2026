<?php

namespace App\Http\Controllers\Backend\ExpenseManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\ExpenseCategory;
use App\Helpers\CurrentUser;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-category-list', 'web')) {
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
        $expenseCategoryData = ExpenseCategory::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allExpenseCategoryCount = ExpenseCategory::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense category fetched successfully.',
                'status_code' => 200,
                'expenseCategoryData' => $expenseCategoryData,
                'allExpenseCategoryCount' => $allExpenseCategoryCount,
            ], 200);
        }

        return view('backend.expensesManagement.category.index',compact('expenseCategoryData','allExpenseCategoryCount'));
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
        if (!auth()->user()->can('expense-category-create', 'web')) {
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

        if(ExpenseCategory::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense category created successfully.',
                    'status_code' => 200,
                    'expenseCategoryData' => $data
                ], 200);
            }
            
            Toastr::success('Expense category created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleExpenseCategoryData = ExpenseCategory::where('id', $id)->first();
        if(isset($singleExpenseCategoryData) && $singleExpenseCategoryData != null){
            return response()->json([
                'message'   =>  'Expense category loaded successfully.',
                'status_code'   => 200,
                'singleExpenseCategoryData'   => $singleExpenseCategoryData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Expense category not found.!',
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
        if (!auth()->user()->can('expense-category-edit', 'web')) {
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
        $singleExpenseCategoryData = ExpenseCategory::where('id', $id)->first();

        if($singleExpenseCategoryData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense category updated successfully.',
                    'status_code' => 200,
                    'singleExpenseCategoryData' => $singleExpenseCategoryData
                ], 200);
            }

            Toastr::success('Expense category updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-category-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleExpenseCategoryData = ExpenseCategory::where('id', $id)->first();
        if($singleExpenseCategoryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense category deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Expense category deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        ExpenseCategory::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense category activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Expense category activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        ExpenseCategory::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense category in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Expense category in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
