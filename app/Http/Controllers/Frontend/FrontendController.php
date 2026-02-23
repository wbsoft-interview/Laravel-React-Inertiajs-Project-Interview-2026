<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function index()
    {	
        return redirect()->route('admin.login');
        $todayDate = Carbon::now()->today()->toDateString();

        return view('frontend.dashboard', compact('todayDate'));
    }

    //To fetch the privacy  policy...
    public function privacyPolicy()
    {   
        return view('frontend.privacyPolicy');
    }
    
    //To fetch the access denied page...
    public function accessDenied()
    {   
        return view('error.accessDenied');
    }

}
