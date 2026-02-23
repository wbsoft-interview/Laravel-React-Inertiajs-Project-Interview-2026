<?php

namespace App\Http\Controllers\Backend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Account;
use App\Models\AccountTransfer;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('account-list', 'web')) {
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
        $accountData = Account::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allAccountCount = Account::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'accountData' => $accountData,
                    'allAccountCount' => $allAccountCount,
                ],
                'message' => 'Account categories fetched successfully.',
            ]);
        }

        return view('backend.account.index',compact('accountData','allAccountCount'));
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
        if (!auth()->user()->can('account-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'account_holder_name'=> 'required',
            'account_name'=> 'required',
            'account_number'=> 'required|unique:accounts',
            'account_balance'=> 'required'
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Account::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Account created successfully.',
                    'status_code' => 200,
                    'accountData' => $data
                ], 200);
            }
            
            Toastr::success('Account created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleAccountData = Account::where('id', $id)->first();
        if(isset($singleAccountData) && $singleAccountData != null){
            return response()->json([
                'message'   =>  'Account loaded successfully.',
                'status_code'   => 200,
                'singleAccountData'   => $singleAccountData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Account not found.!',
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
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'account_holder_name'=> 'required',
            'account_name'=> 'required',
            'account_number'=> 'required|unique:accounts,account_number,'.$id,
            'account_balance'=> 'required'
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleAccountData = Account::where('id', $id)->first();

        if($singleAccountData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Account updated successfully.',
                    'status_code' => 200,
                    'singleAccountData' => $singleAccountData
                ], 200);
            }

            Toastr::success('Account updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('account-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleAccountData = Account::where('id', $id)->first();
        if($singleAccountData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Account deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Account deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Account::where('id', $id)->update(['status' => true]);
        Account::whereNotIn('id', [$id])->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Account activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Account activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Account::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Account in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Account in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To get profile page...
    public function accountProfile(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleAccountData = Account::where('id', $id)->first();
        $accountTransferData = AccountTransfer::orderBy('transfer_date', 'desc')->where('account_id', $singleAccountData->id)->paginate(10);
        $allAccountTransferCount = AccountTransfer::orderBy('transfer_date', 'desc')->where('account_id', $singleAccountData->id)->count();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Account loaded successfully.',
                'status_code' => 200,
                'singleAccountData' => $singleAccountData,
                'accountTransferData' => $accountTransferData,
                'allAccountTransferCount' => $allAccountTransferCount
            ], 200);
        }

        return view('backend.account.profile.index', compact('singleAccountData','accountTransferData','allAccountTransferCount'));
    }

    //To add new transfer...
    public function addTranserData(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('account-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'account_id'=> 'required',
            'transfer_type'=> 'required',
            'transfer_amount'=> 'required',
            'transfer_date'=> 'required',
            'transfer_purpuse'=> 'nullable',
        ]);

        //To fetch userId..
        $data = $request->all();
        $data['transfer_by'] = Auth::user()->id;
        $data['transfer_date'] = Carbon::parse($request->transfer_date)->format('Y-m-d');

        if($newData = AccountTransfer::create($data)){
            //Check transfer type...
            $singleAccountData = Account::where('id', $request->account_id)->first();
            if($request->transfer_type == 'Credit'){
                $singleAccountData->account_balance += $request->transfer_amount;
            }else{
                $singleAccountData->account_balance -= $request->transfer_amount;
            }

            //To update account current amount...
            AccountTransfer::where('id', $newData->id)->update(['current_amount'=>$singleAccountData->account_balance]);
            $singleAccountData->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Transfer amount added successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Transfer amount added successfully.', 'Success', ["progressbar" => true]);
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
    
    //To update transfer...
    public function updateTranserData(Request $request)
    {
        $request->validate([
            'account_id'=> 'required',
            'account_transfer_id'=> 'required',
            'transfer_type'=> 'required',
            'transfer_amount'=> 'required',
            'transfer_date'=> 'required',
            'transfer_purpuse'=> 'nullable',
        ]);

        //To fetch userId..
        $data = $request->all();
        $data['transfer_by'] = Auth::user()->id;
        $data['transfer_date'] = Carbon::parse($request->transfer_date)->format('Y-m-d');
        $singleAccountTransferData = AccountTransfer::where('id', $request->account_transfer_id)->first();
        $prevBalance = $singleAccountTransferData->transfer_amount;
        // dd($prevBalance);
        if($newData = $singleAccountTransferData->update($data)){
            //Check transfer type...
            $singleAccountData = Account::where('id', $request->account_id)->first();
            if($request->transfer_type == 'Credit'){
                //To check previoud balance...
                if($prevBalance > $request->transfer_amount){
                    $remainingBalance = $prevBalance - $request->transfer_amount;
                    $singleAccountData->account_balance -= $remainingBalance;

                }elseif($prevBalance < $request->transfer_amount){
                    $remainingBalance = $request->transfer_amount - $prevBalance;
                    $singleAccountData->account_balance += $remainingBalance;
                }
            }else{
                //To check previoud balance...
                if($prevBalance > $request->transfer_amount){
                    $remainingBalance = $prevBalance - $request->transfer_amount;
                    $singleAccountData->account_balance += $remainingBalance;

                }else if($prevBalance < $request->transfer_amount){
                    $remainingBalance = $request->transfer_amount - $prevBalance;
                    $singleAccountData->account_balance -= $remainingBalance;
                }
            }

            //To update account current amount...
            AccountTransfer::where('id', $singleAccountTransferData->id)->update(['current_amount'=>$singleAccountData->account_balance]);
            $singleAccountData->save();

            Toastr::success('Transfer amount updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
