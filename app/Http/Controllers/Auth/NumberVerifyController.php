<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\SendSMSController;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NumberVerifyController extends Controller
{
    public function index()
    {	
    	$logo = DB::table('logos')->first();
    	return view('auth.numberVerify' ,compact('logo'));
    }

     public function verifyOtp(Request $request)
    {

        $user = auth()->user();
        $verify_code_mas = User::where('verify_code', $request->verify_code)->first();

        if($verify_code_mas)
        {
            if( $verify_code_mas->verify_expires_at < (Carbon::now())){
                $verify_code_mas->verify_code = null;
                $verify_code_mas->verify_expires_at = null;
                $verify_code_mas->save();

                if($request->mobile){
                    $userMobile = $request->mobile;
                    return view('auth.updatePassword',compact('userMobile'));
                }else{
                    $this->guard('web')->login($user);
                    return redirect()->route('homepage');
                }
                
            }else{
                $verify_code_mas->verify_code = null;
                $verify_code_mas->verify_expires_at = null;
                $verify_code_mas->status = 1;
                $verify_code_mas->save();

                if($request->mobile){
                    $userMobile = $request->mobile;
                    return view('auth.updatePassword',compact('userMobile'));
                }else{
                    return redirect()->route('login');
                }
                
            }
        }

        return redirect()->back();
    }



    public function resendOtp($id)
    {
        $user = User::find($id);
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        if($user->save()){
            /*mobile send SMS*/
            $contact = $user->mobile;
            $text = 'Congratulations! Your Verify Otp. '. $user->verify_code;
            SendSMSController::sendSMS($contact,$text);
            
            return redirect()->back()->withErrors(['otp_again' => 
                'The opt has been sent again!.']);
        }else{
            return redirect()->back();
        }
    }


    //To get the resetpassword page...
    public function getResetPassword()
    {
        return view('auth.getResetPassword');
    }

    //To Reset Password...
    public function resetPassword(Request $request)
    {
        $request->validate([
            'mobile' => ['required'],
        ]);

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        $mobileNumber = $request->mobile;
        $text = 'Congratulations! Your Login OTP code is: '.$data['verify_code'].' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        SendSMSController::sendSMS($mobileNumber,$text);

        $userData = User::where('mobile', $request->mobile)->first();
        if(isset($userData)){
            if($userData->update($data)){
                $userMobile = $userData->mobile;
                return view('auth.numberVerify',compact('userMobile'));
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
        
    }

    //To password update...
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required'],
            'password' => ['required', 'confirmed']
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        if($request->password == $request->password_confirmation){
            $data['password'] = Hash::make($request->password);

            $user = User::where('mobile', $request->mobile)->first();
            if($user->update($data)){
                return redirect()->route('login');
            }else{
                return redirect()->back();
            }
        }
        else{
            return redirect()->back();
        }
    }
}
