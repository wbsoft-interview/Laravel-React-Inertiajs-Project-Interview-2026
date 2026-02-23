<?php

namespace App\Http\Controllers\Backend\ExpenseManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Payee;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\ExpenseReceipt;
use App\Models\ExpenseReceiptService;
use App\Models\ExpenseReceiptPayment;
use App\Models\InvoiceLogo;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;

class ExpenseReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-list', 'web')) {
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
        $expenseReceiptData = ExpenseReceipt::orderBy('id', 'desc')->where('status', true)->paginate(10);
        $allExpenseReceiptCount = ExpenseReceipt::orderBy('id', 'desc')->where('status', true)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense fetched successfully.',
                'status_code' => 200,
                'expenseReceiptData' => $expenseReceiptData,
                'allExpenseReceiptCount' => $allExpenseReceiptCount,
            ], 200);
        }

        return view('backend.expensesManagement.expenseReceipt.index',compact('expenseReceiptData','allExpenseReceiptCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $userIdFCU = CurrentUser::getUserIdFCU();
        //To check ExpenseReceipt status...
        $previousExpenseReceiptData = ExpenseReceipt::getPendingExpenseReceiptData($userId, $userIdFCU);
        if(!empty($previousExpenseReceiptData)){
            ExpenseReceipt::whereIn('id', $previousExpenseReceiptData)->delete();
        }

        //To add ExpenseReceipt data...
        $singleExpenseReceiptData = $this->addExpenseReceiptData($userId);

        //To get expene category & expense data...
        $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
        $expenseData = Expense::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
        $payeeData = Payee::orderBy('payee_name','asc')->where('user_id', $userId)->get();
        $unpaidExpenseServiceData[] = null;
        $totalExpenseServiceQty = 0;
        $totalExpenseServiceAmount = 0;

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Expense fetched successfully.',
                'status_code' => 200,
                'expenseCategoryData' => $expenseCategoryData,
                'expenseData' => $expenseData,
                'payeeData' => $payeeData,
                'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
                'totalExpenseServiceQty' => $totalExpenseServiceQty,
                'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
                'singleExpenseReceiptData' => $singleExpenseReceiptData,
            ], 200);
        }

        return view('backend.expensesManagement.expenseReceipt.create',compact('expenseCategoryData','expenseData','payeeData','unpaidExpenseServiceData'
                ,'totalExpenseServiceQty','totalExpenseServiceAmount','singleExpenseReceiptData'));
    }

    //To add new ExpenseReceipt data....
    public function addExpenseReceiptData($userId)
    {
        $userIdFCU = CurrentUser::getUserIdFCU();
        $data = new ExpenseReceipt();
        $data->user_id = $userId;
        $data->created_by = $userIdFCU;
        $data->receipt_by = Auth::user()->name;
        
        if($data->save()){
            return $data;
        }else{
            $data = null;
        }
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
        if (!auth()->user()->can('expense-receipt-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'expense_receipt_id'=> 'required',
            'expense_category_id'=> 'required',
            'expense_id'=> 'required',
            'payee_id'=> 'required',
            'expense_amount'=> 'required',
            'expense_details'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(ExpenseReceiptService::create($data)){
            $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->get();
            $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->count();
            $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->sum('expense_amount');
            $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $expenseData = Expense::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();  
            $payeeData = Payee::orderBy('payee_name','asc')->where('user_id', $userId)->get();  

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Expense fetched successfully.',
            //         'status_code' => 200,
            //         'expenseCategoryData' => $expenseCategoryData,
            //         'expenseData' => $expenseData,
            //         'payeeData' => $payeeData,
            //         'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
            //         'totalExpenseServiceQty' => $totalExpenseServiceQty,
            //         'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
            //     ], 200);
            // }
    
            return view('backend.expensesManagement.expenseReceipt.updateExpenseDetails',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                    ,'totalExpenseServiceAmount','expenseCategoryData','expenseData','payeeData'));
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            return response()->json([
                'error' => 'Sorry, Something is wrong.!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $singleExpenseReceiptData = ExpenseReceipt::where('id', $id)->first();
        $expenseReceiptId = $singleExpenseReceiptData->id;
        if($singleExpenseReceiptData != null){
            $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->get();
            $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->count();
            $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->sum('expense_amount');
         
            $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $expenseData = Expense::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();  
            $payeeData = Payee::orderBy('payee_name','asc')->where('user_id', $userId)->get(); 

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Expense fetched successfully.',
            //         'status_code' => 200,
            //         'expenseCategoryData' => $expenseCategoryData,
            //         'expenseData' => $expenseData,
            //         'payeeData' => $payeeData,
            //         'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
            //         'totalExpenseServiceQty' => $totalExpenseServiceQty,
            //         'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
            //         'singleExpenseReceiptData' => $singleExpenseReceiptData,
            //     ], 200);
            // }

            return view('backend.expensesManagement.expenseReceipt.edit',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                    ,'totalExpenseServiceAmount','expenseCategoryData','expenseData','payeeData','singleExpenseReceiptData'));
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            return response()->json([
                'error' => 'Sorry, Something is wrong.!'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'expense_service_id'=> 'required',
            'expense_receipt_id'=> 'required',
            'expense_category_id'=> 'required',
            'expense_id'=> 'required',
            'payee_id'=> 'required',
            'expense_amount'=> 'required',
            'expense_details'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $singleExpenseReceiptService = ExpenseReceiptService::where('id', $request->expense_service_id)->first();
        $data = $request->all();
        $data['user_id'] = $userId;

        if($singleExpenseReceiptService->update($data)){
            $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->get();
            $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->count();
            $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $request->expense_receipt_id)->sum('expense_amount');
            $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $expenseData = Expense::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();  
            $payeeData = Payee::orderBy('payee_name','asc')->where('user_id', $userId)->get(); 

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Expense fetched successfully.',
            //         'status_code' => 200,
            //         'expenseCategoryData' => $expenseCategoryData,
            //         'expenseData' => $expenseData,
            //         'payeeData' => $payeeData,
            //         'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
            //         'totalExpenseServiceQty' => $totalExpenseServiceQty,
            //         'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
            //     ], 200);
            // }

            return view('backend.expensesManagement.expenseReceipt.updateExpenseDetails',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                    ,'totalExpenseServiceAmount','expenseCategoryData','expenseData','payeeData'));
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            return response()->json([
                'error' => 'Sorry, Something is wrong.!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleExpenseReceiptServiceData = ExpenseReceiptService::where('id', $id)->first();
        if($singleExpenseReceiptServiceData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Expense receipt service deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Expense receipt service deleted successfully.', 'Success', ["progressbar" => true]);
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

    //To remove expene service data...
    public function removeExpenseService(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('expense-receipt-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $singleExpenseReceiptServiceData = ExpenseReceiptService::where('id', $request->expense_service_id)->first();
        $expenseReceiptId = $singleExpenseReceiptServiceData->expense_receipt_id;
        if($singleExpenseReceiptServiceData->delete()){
            $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->get();
            $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->count();
            $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->sum('expense_amount');
         
            $expenseCategoryData = ExpenseCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $expenseData = Expense::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();  
            $payeeData = Payee::orderBy('payee_name','asc')->where('user_id', $userId)->get(); 

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Expense fetched successfully.',
            //         'status_code' => 200,
            //         'expenseCategoryData' => $expenseCategoryData,
            //         'expenseData' => $expenseData,
            //         'payeeData' => $payeeData,
            //         'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
            //         'totalExpenseServiceQty' => $totalExpenseServiceQty,
            //         'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
            //     ], 200);
            // }

            return view('backend.expensesManagement.expenseReceipt.updateExpenseDetails',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                    ,'totalExpenseServiceAmount','expenseCategoryData','expenseData','payeeData'));
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

    //To save all the expense receipt data...
    public function saveAllExpenseReceipt(Request $request)
    {
        $singleExpenseReceiptData = ExpenseReceipt::where('id', $request->expense_receipt_id)->first();
        $singleExpenseReceiptData->receipt_notes = $request->receipt_notes;

        //To get billing service product data...
        $expenseReceiptId = $singleExpenseReceiptData->id;
        $expenseRSD = ExpenseReceiptService::orderBy('id','desc')
                                ->where('expense_receipt_id', $expenseReceiptId)->get();;
        if(isset($expenseRSD) && $expenseRSD != null){
            //To update expense receiptid...
            $nextExpenseReceiptId = ExpenseReceipt::max('expense_receipt_id') + 1;
            if($nextExpenseReceiptId != 1){
                if($singleExpenseReceiptData->expense_receipt_id == null){
                    $singleExpenseReceiptData->expense_receipt_id = $nextExpenseReceiptId;
                }
            }else{
                $singleExpenseReceiptData->expense_receipt_id = 10001;
            }

            if($singleExpenseReceiptData->save()){
                $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                                ->where('expense_receipt_id', $expenseReceiptId)->get();
                $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                            ->where('expense_receipt_id', $expenseReceiptId)->count();
                $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                            ->where('expense_receipt_id', $expenseReceiptId)->sum('expense_amount');

                return view('backend.expensesManagement.expenseReceipt.getExpenseDetailsPage',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                        ,'totalExpenseServiceAmount','singleExpenseReceiptData'));
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
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, You did not create any expense receipt.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, You did not create any expense receipt.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To get all the expense receipt data...
    public function getAllExpenseReceipt($id)
    {
        $singleExpenseReceiptData = ExpenseReceipt::where('id', $id)->first();

        //To get billing service product data...
        $expenseReceiptId = $singleExpenseReceiptData->id;
        $expenseRSD = ExpenseReceiptService::orderBy('id','desc')
                                ->where('expense_receipt_id', $expenseReceiptId)->get();;
        if(isset($expenseRSD) && $expenseRSD != null){
            $unpaidExpenseServiceData = ExpenseReceiptService::orderBy('id','desc')
                            ->where('expense_receipt_id', $expenseReceiptId)->get();
            $totalExpenseServiceQty = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->count();
            $totalExpenseServiceAmount = ExpenseReceiptService::orderBy('id','desc')
                        ->where('expense_receipt_id', $expenseReceiptId)->sum('expense_amount');

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Expense fetched successfully.',
            //         'status_code' => 200,
            //         'unpaidExpenseServiceData' => $unpaidExpenseServiceData,
            //         'totalExpenseServiceQty' => $totalExpenseServiceQty,
            //         'totalExpenseServiceAmount' => $totalExpenseServiceAmount,
            //         'singleExpenseReceiptData' => $singleExpenseReceiptData,
            //     ], 200);
            // }
            
            return view('backend.expensesManagement.expenseReceipt.getExpenseDetailsPage',compact('unpaidExpenseServiceData','totalExpenseServiceQty'
                    ,'totalExpenseServiceAmount','singleExpenseReceiptData'));
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, You did not create any expense receipt.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, You did not create any expense receipt.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get all the expense data with category wise...
    public function getExpenseWithCategory(Request $request)
    {
        $request->validate([
            'expense_category_id'=> 'required',
        ]);

        $expenseCategoryId = $request->expense_category_id;
        $data = Expense::where('expense_category_id', $request->expense_category_id)->get();
        
        return response()->json($data);
    }

    //To add bill payment...
    public function addBillPayment(Request $request)
    {
        $request->validate([
            'expense_receipt_id'=> 'required',
            'total_product'=> 'required',
            'total_amount'=> 'required',
            'grand_total_paid'=> 'required',
            'due_amount'=> 'required',
            'change_amount'=> 'required',
            'paid_expense_amount' => 'required|array',
            'expense_receipt_service_id' => 'required|array',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $currentMonth = Carbon::now()->format('F');
        $currentDate = Carbon::now()->toDateString();

        $data = $request->all();
        $data['user_id'] = $userId;
        $data['billing_month'] = $currentMonth;
        $data['billing_date'] = $currentDate;
        $data['net_amount'] = $request->total_amount;
        $data['paid_amount'] = $request->grand_total_paid;

        //To check billing status...
        $singleBillingData = ExpenseReceipt::where('id', $request->expense_receipt_id)->first();
        if(isset($singleBillingData) && $singleBillingData->status != true){
            if($singleBillPaymentData = ExpenseReceiptPayment::create($data)){

                $paidAmounts = $request->paid_expense_amount;
                $labourBPPIds = $request->expense_receipt_service_id;
                foreach ($labourBPPIds as $index => $bppId) {
                    $singleRow = ExpenseReceiptService::find($bppId);
                    if (!$singleRow) continue;

                    $totalWage = $singleRow->expense_amount ?? 0;
                    $paid = floatval($paidAmounts[$index] ?? 0);
                    $paid = max($paid, 0);
                    $grandDue = max($totalWage - $paid, 0);

                    $singleRow->grand_total_paid = $paid;
                    $singleRow->grand_total_due = $grandDue;
                    $singleRow->save();

                    //Could be in future labour payment details...
                }

                //To update billing status...
                if($request->grand_total_paid >= $request->grand_total_price){
                    $singleBillingData->is_bill_pay = true;
                }
                $singleBillingData->status = true;
                if($singleBillingData->save()){

                    if($request->grand_total_paid >= $request->grand_total_price){
                        Toastr::success('Expense receipt payment added successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('get-expense-receipt-payment-invoice', $request->expense_receipt_id);
                    }else{
                        Toastr::success('Expense receipt payment added successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('expense-receipt.index');
                    }
                }
            }
        }else{
            Toastr::error('Expense receipt paid completed, Try to new Expense receipt.!', 'Error', ["progressbar" => true]);
            return redirect()->route('expense-receipt.create');
        }
    }

    //To get billing payment invoice page...
    public function pendingBillPaymentInvoice($id)
    {
        //To get single billing data...
        $singleBillingData = ExpenseReceipt::where('id', $id)->first();

        //To get billing service product data...
        $serviceBPP = ExpenseReceiptService::where('expense_receipt_id', $id)->get();

        //Total price, discount, qty & grand total price...
        $subTotalPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $subTotalProductQty = ExpenseReceiptService::where('expense_receipt_id', $id)->count();
        $grandTotalProductPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $grandTotalProductPaidPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_paid');
        $grandTotalProductDuePrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_due');

        return view('backend.expensesManagement.expenseReceipt.servicePendingPaymentProductInvoice',compact('serviceBPP','singleBillingData','subTotalPrice'
                    ,'subTotalProductQty','grandTotalProductPrice','grandTotalProductPaidPrice','grandTotalProductDuePrice'));
    }

    //To add bill payment...
    public function addBillPaymentFD(Request $request)
    {
        $request->validate([
            'expense_receipt_id'=> 'required',
            'paid_expense_amount' => 'required|array',
            'expense_receipt_service_id' => 'required|array',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();

        //To check billing status...
        $singleBillingData = ExpenseReceipt::where('id', $request->expense_receipt_id)->first();
        $singleBillingPayData = ExpenseReceiptPayment::where('expense_receipt_id', $request->expense_receipt_id)->first();
        if(isset($singleBillingData) && $singleBillingData->is_bill_pay != true){

            $paidAmounts = $request->paid_expense_amount;
            $labourBPPIds = $request->expense_receipt_service_id;
            foreach ($labourBPPIds as $index => $bppId) {
                $singleRow = ExpenseReceiptService::find($bppId);
                if (!$singleRow) continue;

                $totalWage = $singleRow->grand_total_due ?? 0;
                $paid = floatval($paidAmounts[$index] ?? 0);
                $paid = max($paid, 0);
                $grandDue = max($totalWage - $paid, 0);

                $singleRow->grand_total_paid += $paid;
                $singleRow->grand_total_due = $grandDue;
                $singleRow->save();

                //Could be in future labour payment details...
            }

            //To update billing status...
            if($request->grand_total_paid >= $request->grand_total_due){
                $singleBillingData->is_bill_pay = true;
                if($singleBillingData->save()){
                    $singleBillingPayData->paid_amount += $request->grand_total_paid;
                    $singleBillingPayData->due_amount -= $request->grand_total_paid;
                    $singleBillingPayData->save();
                    
                    Toastr::success('Expense receipt payment added successfully.', 'Success', ["progressbar" => true]);
                    return redirect()->route('get-expense-receipt-payment-invoice', $request->expense_receipt_id);
                }
            }else{
                Toastr::success('Expense receipt payment added successfully.', 'Success', ["progressbar" => true]);
                return redirect()->route('expense-receipt.index');
            }
            
        }else{
            Toastr::error('Expense receipt paid completed, Try to new expense receipt.!', 'Error', ["progressbar" => true]);
            return redirect()->route('expense-receipt.create');
        }
    }

    //To get billing payment invoice page...
    public function getBillPaymentInvoice($id)
    {
        //To get single billing data...
        $singleBillingData = ExpenseReceipt::where('id', $id)->first();

        //To get billing service product data...
        $serviceBPP = ExpenseReceiptService::where('expense_receipt_id', $id)->get();

        //Total price, discount, qty & grand total price...
        $subTotalPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $subTotalProductQty = ExpenseReceiptService::where('expense_receipt_id', $id)->count();
        $grandTotalProductPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $grandTotalProductPaidPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_paid');
        $grandTotalProductDuePrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_due');

        return view('backend.expensesManagement.expenseReceipt.serviceBillPaymentProductInvoice',compact('serviceBPP','singleBillingData','subTotalPrice'
                    ,'subTotalProductQty','grandTotalProductPrice','grandTotalProductPaidPrice','grandTotalProductDuePrice'));
    }

    //To print service bill payment invoice page...
    public function printBillPayInvoicePage($id)
    {
        //To get single logo...
        $logoData = InvoiceLogo::getSoftwareInvoiceLogo();
        if(isset($logoData) && $logoData != null){
            $logoImg = $logoData->logo_image;
        }else{
            $logoImg = null;
        }

        //To get today date...
        $todayDate = Carbon::now()->today()->toDateString();
        $invoiceBackColor = "#fffffff";

        //To get single billing data...
        $singleBillingData = ExpenseReceipt::where('id', $id)->first();
        //To get billing service product data...
        $serviceBPP = ExpenseReceiptService::where('expense_receipt_id', $id)->get();

        //Total price, discount, qty & grand total price...
        $subTotalPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $subTotalProductQty = ExpenseReceiptService::where('expense_receipt_id', $id)->count();
        $grandTotalProductPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $grandTotalProductPaidPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_paid');
        $grandTotalProductDuePrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_due');

        //To generate array-data for print-invoice-page...
        $invoiceData = array(
            'invoice_by' => $singleBillingData->billing_by,
            'invoice' => $singleBillingData->expense_receipt_id,
            'invoice_logo' => $logoImg,
            'date' => $todayDate,

            'invoice_terms' =>  $singleBillingData->invoice_terms_id != null ? $singleBillingData->invoiceTermsData->invoice_terms : '',
            'invoice_color' => $invoiceBackColor,

            'payment_total_amount' => $singleBillingData->expenseReceiptPaymentData->net_amount,
            'billing_service_data' => $serviceBPP,
            'sub_total_price' => $subTotalPrice,
            'sub_total_product_qty' => $subTotalProductQty,
            'grand_total_product_price' => $grandTotalProductPrice,

            'special_discount' => $singleBillingData->expenseReceiptPaymentData->special_discount,
            'paid_amount' => $singleBillingData->expenseReceiptPaymentData->paid_amount,
            'due_amount' => $singleBillingData->expenseReceiptPaymentData->due_amount,
            'change_amount' => $singleBillingData->expenseReceiptPaymentData->change_amount,
        );

        return view('backend.printInvoice.expenseReceipt.printVoucherPaymentInvoice', compact('invoiceData'));
    }
    
    //To download service bill payment invoice page...
    public function downloadBillPayInvoicePage($id)
    {
        //To get single logo...
        $logoData = InvoiceLogo::getSoftwareInvoiceLogo();
        if(isset($logoData) && $logoData != null){
            $logoImg = $logoData->logo_image;
        }else{
            $logoImg = null;
        }

        //To get today date...
        $todayDate = Carbon::now()->today()->toDateString();
        $invoiceBackColor = "#fffffff";

        //To get single billing data...
        $singleBillingData = ExpenseReceipt::where('id', $id)->first();
        //To get billing service product data...
        $serviceBPP = ExpenseReceiptService::where('expense_receipt_id', $id)->get();

        //Total price, discount, qty & grand total price...
        $subTotalPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $subTotalProductQty = ExpenseReceiptService::where('expense_receipt_id', $id)->count();
        $grandTotalProductPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('expense_amount');
        $grandTotalProductPaidPrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_paid');
        $grandTotalProductDuePrice = ExpenseReceiptService::where('expense_receipt_id', $id)->sum('grand_total_due');

        //To generate array-data for print-invoice-page...
        $invoiceData = array(
            'invoice_by' => $singleBillingData->billing_by,
            'invoice' => $singleBillingData->expense_receipt_id,
            'invoice_logo' => $logoImg,
            'date' => $todayDate,

            'invoice_terms' =>  $singleBillingData->invoice_terms_id != null ? $singleBillingData->invoiceTermsData->invoice_terms : '',
            'invoice_color' => $invoiceBackColor,

            'payment_total_amount' => $singleBillingData->expenseReceiptPaymentData->net_amount,
            'billing_service_data' => $serviceBPP,
            'sub_total_price' => $subTotalPrice,
            'sub_total_product_qty' => $subTotalProductQty,
            'grand_total_product_price' => $grandTotalProductPrice,

            'special_discount' => $singleBillingData->expenseReceiptPaymentData->special_discount,
            'paid_amount' => $singleBillingData->expenseReceiptPaymentData->paid_amount,
            'due_amount' => $singleBillingData->expenseReceiptPaymentData->due_amount,
            'change_amount' => $singleBillingData->expenseReceiptPaymentData->change_amount,
        );

        // $pdf = PDF::loadView('backend.printInvoice.billPaymentInvoice', compact('invoiceData'));
        // return $pdf->download('serviceBillPayInvoice.pdf');

        return view('backend.printInvoice.expenseReceipt.downloadVoucherPaymentInvoice', compact('invoiceData'));
    }

    //To delete billing...
    public function deleteExpenseService($id)
    {
        //To get single billing data...
        $singleBillingData = ExpenseReceipt::where('id', $id)->first();
        if($singleBillingData->delete()){
            Toastr::success('Expense receipt deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('expense-receipt.create');
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
