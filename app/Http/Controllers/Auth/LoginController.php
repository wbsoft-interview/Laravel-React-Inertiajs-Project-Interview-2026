<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Mail\ForgotPasswordSend;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    //To get login page...
    public function getLoginPageForAdmin()
    {
        return redirect()->route('admin.login');
    }
    
    //To get login page...
    public function getLoginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {   
        $request->validate([
            'email' => 'nullable',
            'mobile' => 'nullable',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        //To check email or mobile...
        if($request->email != null){
            //To check user is avaiable or not with loginId...
            $singleUser = User::where('email', $request->email)->first();

            //to check user is available or not...
            if(isset($singleUser) && $singleUser != null){
                //To login with login_id...
                if($this->guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                    return redirect()->route('admin.dashboard');
                }else{
                    Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', 'Error', ["progressbar" => true]);
                return redirect()->route('login');
            }
        }else if($request->mobile != null){
            //To check user is avaiable or not with loginId...
            $singleUser = User::where('mobile', $request->mobile)->first();

            //to check user is available or not...
            if(isset($singleUser) && $singleUser != null){
                //To login with login_id...
                if($this->guard()->attempt(['mobile' => $request->mobile, 'password' => $request->password], $request->remember)) {
                    return redirect()->route('user-dashboard');
                }else{
                    Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', 'Error', ["progressbar" => true]);
                return redirect()->route('login');
            }
        }else{
            Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get forgot password page...
    public function forgotPassword()
    {
        return view('backend.auth.forgotPassword');
    }

    //To get forgot password page...
    public function forgotPasswordOTPSent(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        //To get single user data...
        $user = User::where('email',$request->email)->first();

        //To check user is null or not...
        if(isset($user) && $user != null){
            $user->verify_code = rand(100000, 999999);
            $user->verify_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            /*email send SMS*/
            $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
            $this->sendSMSToEmail($user->email,$text,$user);

            $userEmail = Crypt::encrypt($request->email);
            Toastr::success('Check email, Please verify otp.', '', ["progressbar" => true]);
            return redirect()->route('get-forgot-pass-verify-OTP-page', ['user_email' => $userEmail]);
        }else{
            Toastr::error('Sorry, You have entered wrong email.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //For send sms to email..
    public function sendSMSToEmail($email,$text,$user)
    {
        $mail_details = [
            'subject' => 'Master-Panel Software Ltd.',
            'body' => $text,
            'userData' => $user,
        ];

        \Mail::to($email)->send(new ForgotPasswordSend($mail_details));
    }

    //To get user verify otp page...
    public function getForgotVerifyOtpPage(Request $request, $userEmail)
    {
        $userEmail = Crypt::decrypt($userEmail);
        return view('backend.auth.verifyOTPForForgotPass', compact('userEmail'));
    }

    //To again resend otp for password change...
    public function resendOtpForPassChange($userEmail)
    {
        //To decript...
        $email = Crypt::decrypt($userEmail);
        $user = User::where('email',$email)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*email send SMS*/
        $text = 'Congratulations! Your verify OTP code is: '.$user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMSToEmail($user->email,$text,$user);

        //To check user is null or not...
        if(isset($user) && $user != null){
            $userEmail = Crypt::encrypt($email);
            Toastr::success('Success, Check your email, And verify otp.', '', ["progressbar" => true]);
            return redirect()->route('get-forgot-pass-verify-OTP-page', ['user_email' => $userEmail]);
        }else{
            Toastr::error('Sorry, Something is wrong.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To verify OTP for fotgot pass....
    public function forgotPassVerifyOtp(Request $request)
    {
        $singleUserData = User::where('verify_code', $request->verify_code)->first();
   
        if($singleUserData != null){
            if( $singleUserData->verify_expires_at < (Carbon::now())){
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
                
                Toastr::error('Error !! OTP verification time expired, Please resend OTP again.', '', ["progressbar" => true]);
                return redirect()->back();
            
            }else{
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->status = 1;
                $singleUserData->save();

                Toastr::success('Success !! You are now verified, Update password now.', '', ["progressbar" => true]);
                return view('backend.auth.changePassword', compact('singleUserData'));
            }
        }

        Toastr::error('Error !! Sorry, OTP not matching.', '', ["progressbar" => true]);
        return redirect()->route('webuser.get-login');
    }

    //To change password update ...
    public function webUserPasswordUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $singleUserData = User::where('email', $request->email)->first();

        if ($request->new_password == $request->confirm_password) {
            User::find($singleUserData->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Toastr::success('Success, Password updated, Please login now.', '', ["progressbar" => true]);
            return redirect()->route('admin.login');

        }else{
            Toastr::error('Password and Confirm Password do not match.!', '', ["progressbar" => true]);
            return view('backend.auth.changePassword', compact('singleUserData'));
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
