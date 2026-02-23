<?php

namespace App\Http\Controllers\Backend\SMSModule;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\NoticeSMS;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Hash;
use Auth;
use DB;

class SMSDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('sms-count-view-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $userId = CurrentUser::getOwnerId();
        $userIdFCU = CurrentUser::getUserIdFCU();
        $userSupId = CurrentUser::getSuperadminId();
        $adminID = $userId;
        
        //To SMS balance data...
        $userBalanceData = AdminPackageHistory::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $userBalanceCount = AdminPackageHistory::where('user_id', $userId)->count();

        //To get SMS counts...
        $totalSMSCount = AdminPackageHistory::where('user_id', $userSupId)->where('package_by', $userId)->sum('sms_qty');
        $availableSMSCount = AdminPackage::where('user_id', $userSupId)->where('package_by', $userId)->value('sms_remaining');

        //Monthly calculation...
        $startOfMonth = now()->startOfMonth();
        $thisMonthSMS = AdminPackageHistory::where('user_id', $userSupId)
            ->where('package_by', $userId)
            ->where('created_at', '>=', $startOfMonth)
            ->sum('sms_qty');
        $thisMonthCost = $thisMonthSMS * 0.40;

        //Yearly calculation...
        $startOfYear = now()->startOfYear();
        $thisYearSMS = AdminPackageHistory::where('user_id', $userSupId)
            ->where('package_by', $userId)
            ->where('created_at', '>=', $startOfYear)
            ->sum('sms_qty');
        $thisYearCost = $thisYearSMS * 0.40;

        //Monthly report (current year)...
        $year = now()->year;
        $smsReports = [];

        for ($month = 1; $month <= 12; $month++) {

            $start = Carbon::create($year, $month)->startOfMonth();
            $end = Carbon::create($year, $month)->endOfMonth();
            $count = NoticeSMS::where('user_id', $userId)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            $smsReports[] = [
                'month' => $start->format('F'),
                'total' => $count,
                'cost'  => $count * 0.40,
            ];
        }

        // API response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded SMS data.',
                'status_code' => 200,
                'userBalanceData' => $userBalanceData,
                'userBalanceCount' => $userBalanceCount,
            ], 200);
        }

        return view('backend.smsModule.userSMS.dashboard', compact(
            'userBalanceData',
            'userBalanceCount',
            'adminID',
            'totalSMSCount',
            'availableSMSCount',
            'thisMonthSMS',
            'thisMonthCost',
            'thisYearSMS',
            'thisYearCost',
            'smsReports'
        ));
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
        if (!auth()->user()->can('sms-count-view-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'user_id' => 'required',
            'total_price' => 'required',
            'total_sms'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['status'] = false;
        $data['user_id'] = $userId;
        $data['user_id'] = $request->user_id;
        $data['total_sms'] = $request->total_sms;
        $data['purchase_year'] = Carbon::now()->year;
        $data['purchase_month'] = Carbon::now()->format('F');
        $data['purchase_date'] = Carbon::parse($request->purchase_date)->format('Y-m-d');

        if(AdminPackageHistory::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'SMS recharge request successfully sent.',
                    'status_code' => 200,
                ], 200);
            }

            Toastr::success('SMS recharge request successfully sent.', 'Success', ["progressbar" => true]);
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
