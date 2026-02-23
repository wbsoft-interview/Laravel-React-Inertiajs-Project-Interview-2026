<?php

namespace App\Http\Controllers\Backend\ExpenseManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Helpers\CurrentUser;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-list', 'web')) {
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
        $expenseData = Expense::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allExpenseCount = Expense::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense fetched successfully.',
                'status_code' => 200,
                'expenseData' => $expenseData,
                'allExpenseCount' => $allExpenseCount,
                'expenseCategoryData' => $expenseCategoryData,
            ], 200);
        }

        return view('backend.expensesManagement.expense.index',compact('expenseData','allExpenseCount','expenseCategoryData'));
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
        if (!auth()->user()->can('expense-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'expense_category_id'=> 'required',
            'expense_name'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Expense::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense created successfully.',
                    'status_code' => 200,
                    'expenseData' => $data
                ], 200);
            }
            
            Toastr::success('Expense created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleExpenseData = Expense::where('id', $id)->first();
        if(isset($singleExpenseData) && $singleExpenseData != null){
            return response()->json([
                'message'   =>  'Expense loaded successfully.',
                'status_code'   => 200,
                'singleExpenseData'   => $singleExpenseData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Expense not found.!',
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
        if (!auth()->user()->can('expense-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'expense_category_id'=> 'required',
            'expense_name'=> 'required',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleExpenseData = Expense::where('id', $id)->first();

        if($singleExpenseData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense updated successfully.',
                    'status_code' => 200,
                    'singleExpenseData' => $singleExpenseData
                ], 200);
            }

            Toastr::success('Expense updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleExpenseData = Expense::where('id', $id)->first();
        if($singleExpenseData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Expense deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('expense-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Expense::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Expense activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Expense::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Expense in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
