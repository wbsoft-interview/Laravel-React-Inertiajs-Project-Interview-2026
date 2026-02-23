<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Models\Webuser;
use Carbon\Carbon;
use Validator;
use Auth;
use Str;

class WebHomeController extends Controller
{
    //To get frontend home page..
    public function userDashboard(Request $request)
    {
        //To fet category data..
        $singleUserData = Webuser::orderBy('id','asc')->get();
        $todayDate = Carbon::now()->toDateString();
        
        if(!empty($singleUserData)){
            Toastr::success('Success !! You Are Now Loged In User.', '', ["progressbar" => true]);
            return view('frontend.dashboard', compact('singleUserData','todayDate'));
        }else{
            Toastr::error('Error !! Something Is Wrong.', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get web user logout...
    public function webUserLogout()
    {
        Auth::guard('webuser')->logout();
        return redirect(route('webuser.get-login'));
    }
}