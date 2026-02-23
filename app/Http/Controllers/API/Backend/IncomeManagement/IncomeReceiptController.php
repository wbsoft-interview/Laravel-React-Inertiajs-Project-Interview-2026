<?php

namespace App\Http\Controllers\Backend\IncomeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Receiver;
use App\Models\IncomeCategory;
use App\Models\Income;
use App\Models\IncomeReceipt;
use App\Models\IncomeReceiptService;
use App\Helpers\CurrentUser;
use Auth;

class IncomeReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-list', 'web')) {
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
        $incomeReceiptData = IncomeReceipt::orderBy('id', 'desc')->where('status', true)->paginate(10);
        $allIncomeReceiptCount = IncomeReceipt::orderBy('id', 'desc')->where('status', true)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income fetched successfully.',
                'status_code' => 200,
                'incomeReceiptData' => $incomeReceiptData,
                'allIncomeReceiptCount' => $allIncomeReceiptCount,
            ], 200);
        }

        return view('backend.incomeManage.incomeReceipt.index',compact('incomeReceiptData','allIncomeReceiptCount'));
    }

    public function create(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-create', 'web')) {
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
        //To check IncomeReceipt status...
        $previousIncomeReceiptData = IncomeReceipt::getPendingIncomeReceiptData($userId);
        if(!empty($previousIncomeReceiptData)){
            IncomeReceipt::whereIn('id', $previousIncomeReceiptData)->delete();
        }

        //To add IncomeReceipt data...
        $singleIncomeReceiptData = $this->addIncomeReceiptData($userId);

        //To get expene category & income data...
        $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
        $incomeData = Income::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
        $receiverData = Receiver::orderBy('receiver_name','asc')->where('user_id', $userId)->get();
        $unpaidIncomeServiceData[] = null;
        $totalIncomeServiceQty = 0;
        $totalIncomeServiceAmount = 0;

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Income fetched successfully.',
                'status_code' => 200,
                'incomeCategoryData' => $incomeCategoryData,
                'incomeData' => $incomeData,
                'receiverData' => $receiverData,
                'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
                'totalIncomeServiceQty' => $totalIncomeServiceQty,
                'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
                'singleIncomeReceiptData' => $singleIncomeReceiptData,
            ], 200);
        }

        return view('backend.incomeManage.incomeReceipt.create',compact('incomeCategoryData','incomeData','receiverData','unpaidIncomeServiceData'
                ,'totalIncomeServiceQty','totalIncomeServiceAmount','singleIncomeReceiptData'));
    }

    //To add new IncomeReceipt data....
    public function addIncomeReceiptData($userId)
    {
        $data = new IncomeReceipt();
        $data->user_id = $userId;
        $data->receipt_by = Auth::user()->name;

        if($data->save()){
            return $data;
        }else{
            $data = null;
        }
    }

    public function store(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'income_receipt_id'=> 'required',
            'income_category_id'=> 'required',
            'income_id'=> 'required',
            'receiver_id'=> 'required',
            'income_amount'=> 'required',
            'income_details'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(IncomeReceiptService::create($data)){
            $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->get();
            $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->count();
            $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->sum('income_amount');
            $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $incomeData = Income::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $receiverData = Receiver::orderBy('receiver_name','asc')->where('user_id', $userId)->get();

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Income fetched successfully.',
            //         'status_code' => 200,
            //         'IncomeCategoryData' => $IncomeCategoryData,
            //         'IncomeData' => $IncomeData,
            //         'ReceiverData' => $receiverData,
            //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
            //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
            //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
            //     ], 200);
            // }

            return view('backend.incomeManage.incomeReceipt.updateIncomeDetails',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                    ,'totalIncomeServiceAmount','incomeCategoryData','incomeData','receiverData'));
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

    public function edit($id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-edit', 'web')) {
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
        $singleIncomeReceiptData = IncomeReceipt::where('id', $id)->first();
        $incomeReceiptId = $singleIncomeReceiptData->id;
        if($singleIncomeReceiptData != null){
            $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->get();
            $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->count();
            $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->sum('income_amount');

            $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $incomeData = Income::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $receiverData = Receiver::orderBy('receiver_name','asc')->where('user_id', $userId)->get();

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Income fetched successfully.',
            //         'status_code' => 200,
            //         'incomeCategoryData' => $incomeCategoryData,
            //         'incomeData' => $incomeData,
            //         'receiverData' => $receiverData,
            //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
            //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
            //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
            //         'singleIncomeReceiptData' => $singleIncomeReceiptData,
            //     ], 200);
            // }

            return view('backend.incomeManage.incomeReceipt.edit',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                    ,'totalIncomeServiceAmount','incomeCategoryData','incomeData','receiverData','singleIncomeReceiptData'));
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

    public function update(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'income_service_id'=> 'required',
            'income_receipt_id'=> 'required',
            'income_category_id'=> 'required',
            'income_id'=> 'required',
            'receiver_id'=> 'required',
            'income_amount'=> 'required',
            'income_details'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getOwnerId();
        $singleIncomeReceiptService = IncomeReceiptService::where('id', $request->income_service_id)->first();
        $data = $request->all();
        $data['user_id'] = $userId;

        if($singleIncomeReceiptService->update($data)){
            $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->get();
            $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->count();
            $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $request->income_receipt_id)->sum('income_amount');
            $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $incomeData = Income::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $receiverData = Receiver::orderBy('receiver_name','asc')->where('user_id', $userId)->get();

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Income fetched successfully.',
            //         'status_code' => 200,
            //         'incomeCategoryData' => $incomeCategoryData,
            //         'incomeData' => $incomeData,
            //         'receiverData' => $receiverData,
            //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
            //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
            //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
            //     ], 200);
            // }

            return view('backend.incomeManage.incomeReceipt.updateIncomeDetails',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                    ,'totalIncomeServiceAmount','incomeCategoryData','incomeData','receiverData'));
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

    public function destroy($id)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleIncomeReceiptServiceData = IncomeReceiptService::where('id', $id)->first();
        if($singleIncomeReceiptServiceData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Income receipt service deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Income receipt service deleted successfully.', 'Success', ["progressbar" => true]);
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
    public function removeIncomeService(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('income-receipt-delete', 'web')) {
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
        $singleIncomeReceiptServiceData = IncomeReceiptService::where('id', $request->income_service_id)->first();
        $incomeReceiptId = $singleIncomeReceiptServiceData->income_receipt_id;
        if($singleIncomeReceiptServiceData->delete()){
            $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->get();
            $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->count();
            $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->sum('income_amount');

            $incomeCategoryData = IncomeCategory::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $incomeData = Income::orderBy('id','desc')->where('user_id', $userId)->where('status',true)->get();
            $receiverData = Receiver::orderBy('receiver_name','asc')->where('user_id', $userId)->get();

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Income fetched successfully.',
            //         'status_code' => 200,
            //         'incomeCategoryData' => $incomeCategoryData,
            //         'incomeData' => $incomeData,
            //         'receiverData' => $receiverData,
            //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
            //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
            //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
            //     ], 200);
            // }

            return view('backend.incomeManage.incomeReceipt.updateIncomeDetails',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                    ,'totalIncomeServiceAmount','incomeCategoryData','incomeData','receiverData'));
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

    //To save all the income receipt data...
    public function saveAllIncomeReceipt(Request $request)
    {
        $singleIncomeReceiptData = IncomeReceipt::where('id', $request->income_receipt_id)->first();
        $singleIncomeReceiptData->receipt_notes = $request->receipt_notes;
        $singleIncomeReceiptData->status = 1;

        //To get billing service product data...
        $incomeReceiptId = $singleIncomeReceiptData->id;
        $incomeRSD = IncomeReceiptService::orderBy('id','desc')
                                ->where('income_receipt_id', $incomeReceiptId)->get();;
        if(isset($incomeRSD) && $incomeRSD != null){
            //To update income receiptid...
            $nextIncomeReceiptId = IncomeReceipt::max('income_receipt_id') + 1;
            if($nextIncomeReceiptId != 1){
                if($singleIncomeReceiptData->income_receipt_id == null){
                    $singleIncomeReceiptData->income_receipt_id = $nextIncomeReceiptId;
                }
            }else{
                $singleIncomeReceiptData->income_receipt_id = 10001;
            }

            if($singleIncomeReceiptData->save()){
                $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                                ->where('income_receipt_id', $incomeReceiptId)->get();
                $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                            ->where('income_receipt_id', $incomeReceiptId)->count();
                $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                            ->where('income_receipt_id', $incomeReceiptId)->sum('income_amount');

                //To API response...
                // if ($request->expectsJson()) {
                //     return response()->json([
                //         'message' => 'Income fetched successfully.',
                //         'status_code' => 200,
                //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
                //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
                //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
                //         'singleIncomeReceiptData' => $singleIncomeReceiptData,
                //     ], 200);
                // }

                return view('backend.incomeManage.incomeReceipt.getIncomeDetailsPage',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                        ,'totalIncomeServiceAmount','singleIncomeReceiptData'));
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
                    'message' => 'Sorry, You did not create any income receipt.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, You did not create any income receipt.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    //To get all the income receipt data...
    public function getAllIncomeReceipt($id)
    {
        $singleIncomeReceiptData = IncomeReceipt::where('id', $id)->first();

        //To get billing service product data...
        $incomeReceiptId = $singleIncomeReceiptData->id;
        $incomeRSD = IncomeReceiptService::orderBy('id','desc')
                                ->where('income_receipt_id', $incomeReceiptId)->get();;
        if(isset($incomeRSD) && $incomeRSD != null){
            $unpaidIncomeServiceData = IncomeReceiptService::orderBy('id','desc')
                            ->where('income_receipt_id', $incomeReceiptId)->get();
            $totalIncomeServiceQty = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->count();
            $totalIncomeServiceAmount = IncomeReceiptService::orderBy('id','desc')
                        ->where('income_receipt_id', $incomeReceiptId)->sum('income_amount');

            //To API response...
            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message' => 'Income fetched successfully.',
            //         'status_code' => 200,
            //         'unpaidIncomeServiceData' => $unpaidIncomeServiceData,
            //         'totalIncomeServiceQty' => $totalIncomeServiceQty,
            //         'totalIncomeServiceAmount' => $totalIncomeServiceAmount,
            //         'singleIncomeReceiptData' => $singleIncomeReceiptData,
            //     ], 200);
            // }

            return view('backend.incomeManage.incomeReceipt.getIncomeDetailsPage',compact('unpaidIncomeServiceData','totalIncomeServiceQty'
                    ,'totalIncomeServiceAmount','singleIncomeReceiptData'));
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, You did not create any income receipt.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, You did not create any income receipt.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    //To get all the income data with category wise...
    public function getIncomeWithCategory(Request $request)
    {
        $request->validate([
            'income_category_id'=> 'required',
        ]);

        $incomeCategoryId = $request->income_category_id;
        $data = Income::where('income_category_id', $request->income_category_id)->get();

        return response()->json($data);
    }
}
