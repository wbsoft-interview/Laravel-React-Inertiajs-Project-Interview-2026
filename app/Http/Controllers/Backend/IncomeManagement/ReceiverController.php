<?php

namespace App\Http\Controllers\Backend\IncomeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\IncomeCategory;
use App\Models\Receiver;
use App\Models\Income;
use App\Helpers\CurrentUser;

class ReceiverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('receiver-list', 'web')) {
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
        $receiverData = Receiver::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allReceiverCount = Receiver::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Receiver fetched successfully.',
                'status_code' => 200,
                'receiverData' => $receiverData,
                'allReceiverCount' => $allReceiverCount,
            ], 200);
        }

        return view('backend.incomeManage.receiver.index',compact('receiverData','allReceiverCount'));
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
        if (!auth()->user()->can('receiver-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'receiver_name'=> 'required',
            'receiver_phone'=> 'required|min:11|max:11|unique:receivers',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Receiver::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Receiver created successfully.',
                    'status_code' => 200,
                    'receiverData' => $data
                ], 200);
            }

            Toastr::success('Receiver created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('receiver-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleReceiverData = Receiver::where('id', $id)->first();
        if(isset($singleReceiverData) && $singleReceiverData != null){
            return response()->json([
                'message'   =>  'Account loaded successfully.',
                'status_code'   => 200,
                'singleReceiverData'   => $singleReceiverData
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
        if (!auth()->user()->can('receiver-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'receiver_name'=> 'required',
            'receiver_phone'=> 'required|min:11|max:11|unique:receivers,receiver_phone,'.$id,
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleReceiverData = Receiver::where('id', $id)->first();

        if($singleReceiverData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Receiver updated successfully.',
                    'status_code' => 200,
                    'singleReceiverData' => $singleReceiverData
                ], 200);
            }

            Toastr::success('Receiver updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('receiver-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleReceiverData = Receiver::where('id', $id)->first();
        if($singleReceiverData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Receiver deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Receiver deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('receiver-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Receiver::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Receiver activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Receiver activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('receiver-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Receiver::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Receiver in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Receiver in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
