<?php

namespace App\Http\Controllers\Backend\SMSModule;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\SMSTemplate;
use App\Models\User;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Hash;
use Auth;
use DB;

class SMSTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('sms-template-list', 'web')) {
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
        $smsTemplateData = SMSTemplate::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allSMSTemplateCount = SMSTemplate::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'SMS template fetched successfully.',
                'status_code' => 200,
                'smsTemplateData' => $smsTemplateData,
                'allSMSTemplateCount' => $allSMSTemplateCount,
            ], 200);
        }

        return view('backend.smsModule.smsTemplate.index',compact('smsTemplateData','allSMSTemplateCount'));
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
        if (!auth()->user()->can('sms-template-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'sms_title' => 'required',
            'sms_details'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(SMSTemplate::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'SMS template created successfully.',
                    'status_code' => 200,
                    'smsTemplateData' => $data
                ], 200);
            }

            Toastr::success('SMS template created successfully.', 'Success', ["progressbar" => true]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('sms-template-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'sms_title' => 'required',
            'sms_details'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleUSMSBData = SMSTemplate::where('id', $id)->first();

        if($singleUSMSBData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'SMS template updated successfully.',
                    'status_code' => 200,
                    'leaveCategoryData' => $data
                ], 200);
            }

            Toastr::success('SMS template updated successfully.', 'Success', ["progressbar" => true]);
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
}
