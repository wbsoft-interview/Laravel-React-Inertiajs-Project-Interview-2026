<?php

namespace App\Http\Controllers\Backend\SMSModule;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Jobs\SendBulkNoticeSMSJob;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\SessionYear;
use App\Models\UserSMSBalance;
use App\Models\NoticeSMS;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Models\SMSTemplate;
use App\Models\User;
use App\Helpers\CurrentUser;
use Hash;
use Auth;
use DB;

class NoticeSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('notice-sms-list', 'web')) {
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
        $noticeSMSMData = NoticeSMS::orderBy('id','DESC')->where('user_id', $userId)->paginate(10);
        $noticeSMSCount = NoticeSMS::orderBy('id','DESC')->where('user_id', $userId)->count();
        $smsTemplateMData = SMSTemplate::orderBy('id','DESC')->where('user_id', $userId)->get();
        if(Auth::user()->role == 'superadmin'){
            $roleData = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roleData = Role::whereNotIn('name', ['superadmin','admin'])->where('admin_id', $userIdFCU)->get();
        }else{
            $userRoleName = Auth::user()->role;
            $roleData = Role::whereNotIn('name', ['superadmin','admin',$userRoleName])->where('admin_id', $userIdFCU)->get();
        }

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded notice SMS data.',
                'status_code' => 200,
                'noticeSMSMData'   =>  $noticeSMSMData,
                'noticeSMSCount'   =>  $noticeSMSCount,
                'smsTemplateMData'   =>  $smsTemplateMData,
                'roleData'   =>  $roleData,
            ], 200);
        }

        return view('backend.smsModule.noticeSMS.index', compact('noticeSMSMData','noticeSMSCount','smsTemplateMData','roleData'));
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
        if (!auth()->user()->can('notice-sms-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'role_id' => 'required',
            'sms_template_id'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $userIdFCU = CurrentUser::getUserIdFCU();
        $userSupId = CurrentUser::getSuperadminId();
        $singleSMSTemplateData = SMSTemplate::where('id', $request->sms_template_id)->first();
        if (!$singleSMSTemplateData) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! SMS template not found.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! SMS template not found.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        $title = $singleSMSTemplateData->sms_title;
        $details = $singleSMSTemplateData->sms_details;
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['sms_from_id'] = $userIdFCU;
        $smsFromData = User::where('id', $userIdFCU)->first();
        //To get single role data...
        $singleRoleData = Role::where('id', $request->role_id)->first();
        $userData = collect();
        $userData = User::where('role', $singleRoleData->name)->where('admin_id', $userId)->where('status', true)->get();
        $userData = $userData->unique('id')->values();

        //To get admin sms balance...
        $adminSMSBalance = AdminPackage::where('user_id', $userSupId)->where('package_by', $userId)->first();
        if (!$adminSMSBalance) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! SMS not purchase yet now.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! SMS not purchase yet now.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        $requiredSms = $userData->count();
        if ($adminSMSBalance->sms_remaining < $requiredSms) {
            $message = "Not enough SMS balance. You need {$requiredSms}, but you have {$adminSMSBalance->sms_remaining}.";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status_code' => 500
                ], 500);
            }

            Toastr::error($message, 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        dispatch(new SendBulkNoticeSMSJob(
            $userData,
            $title,
            $details,
            $userId,
            $userIdFCU,
            $userSupId,
            $request->sms_template_id
        ));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Notice sms sent successfully.',
                'status_code' => 200,
            ], 200);
        }

        Toastr::success('Notice sms sent successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
