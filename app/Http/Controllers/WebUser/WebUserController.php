<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classname;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Post;
use App\Models\UserPostClick;
use App\Models\Favouritelist;
use App\Models\User;
use App\Models\Webuser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class WebUserController extends Controller
{
    //To get web user registration page...
    public function webUserRegisterPage()
    {
        return view('frontend.auth.register');
    }
    
    //To register web user...
    public function webUserRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'nullable|min:11|max:11',
            'password' => 'required|same:password_confirmation',
        ]);

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        if(!Webuser::where('email', $request->email)->first()){
            //To check email is unique or not...
            if(!Webuser::where('email', $request->email)->first()){
                //
            }else{
                Toastr::error('Error !! This email had already taken.', '', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
            
            if($request->password == $request->password_confirmation){
                $data['password'] = Hash::make($request->password);
                if($newUser = Webuser::create($data)){
                    $userRole = Role::where('name', 'user')->pluck('id');
                    $userData = Webuser::where('email', $request->email)->first();

                    /*email send SMS*/
                    $contact = $request->email;
                    $text = 'Congratulations! Your Login OTP code is: '. $userData->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
                    
                    // $checkStatus = $this->sendSMS($contact,$text);
                    $userEmail = Crypt::encrypt($userData->email);
                    Toastr::success('Success !! Registration completed, Please verify your account.', '', ["progressbar" => true]);
                    return redirect()->route('webuser.get-verify-OTP-page', ['user_email' => $userEmail]);
                }else{
                    Toastr::error('Error !! Something is wrong.', '', ["progressbar" => true]);
                    return redirect()->route('webuser.get-login');
                }
            }
            else{
                Toastr::error('Error !! Password and confirm password not matching.', '', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
        }else{
            Toastr::error('Error !! This number had already taken.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-login');
        }
    }

    //To get user verify otp page...
    public function getVerifyOtpPage(Request $request, $userEmail)
    {
        $userEmail = Crypt::decrypt($userEmail);
        return view('frontend.auth.verifyOTP', compact('userEmail'));
    }

    //To again resend otp...
    public function resendOTP($userEmail)
    {
        //To decript...
        $getUserEmail = Crypt::decrypt($userEmail);

        $user = Webuser::where('email',$getUserEmail)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*email send SMS*/
        $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        // $this->sendSMS($getUserEmail,$text);
        //To check user is null or not...
        if(isset($user) && $user != null){
            $userEmail = Crypt::encrypt($getUserEmail);
            Toastr::success('Check number, Please verify otp.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-verify-OTP-page', ['user_email' => $userEmail]);
        }else{
            Toastr::error('Sorry, Something is wrong.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To verify OTP....
    public function webUserVerifyOtp(Request $request)
    {
        $singleUserData = Webuser::where('verify_code', $request->verify_code)->first();
        if(isset($singleUserData) && $singleUserData != null){
            if( $singleUserData->verify_expires_at < (Carbon::now())){
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
                
                Toastr::error('Error !! OTP verification time expired, Please resend OTP again.', '', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            
            }else{
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->status = 1;
                $singleUserData->save();

                Toastr::success('Success !! You are now verified, Please login.', '', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
           
        }else{
            Toastr::error('Error !! Sorry, OTP not matching.', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To get web user login page...
    public function webUserLoginPage()
    {
        return view('frontend.auth.login');
    }

    //To check web user login form...
    public function webUserogin(Request $request)
    {
        $request->validate([
            'email' => 'nullable',
            'mobile' => 'nullable',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        //To check user is avaiable or not with loginId...
        $singleUser = User::where('email', $request->email)->first();
        //To check user is activated or not...
        if(isset($singleUser) && $singleUser->status == true){
            //To check user is available or not...

            if(isset($singleUser) && $singleUser != null){

                //To login with login_id...
                if(Auth::guard('webuser')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        // dd($singleUser);

                    return redirect()->route('webuser.dashboard');
                }else{
                    dd('asd');
                    Toastr::error('Error !! Someting Is Wrong.', '', ["progressbar" => true]);
                    return redirect()->route('webuser.get-login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', '', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
        }else{
            Toastr::error('Error !! User Not Activated.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-login');
        }
    }

    //To get forgot password page...
    public function forgotPassword()
    {
        return view('frontend.auth.forgotPassword');
    }

    //To get forgot password page...
    public function forgotPasswordOTPSent(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
        ]);

        //To check mobile number..
        if(strlen($request->mobile) != 11){
            Toastr::error('Error !! Mobile number must be 11 digit.!', '', ["progressbar" => true]);
            return redirect()->back();
        }

        //To get single user data...
        $user = User::where('mobile',$request->mobile)->first();

        //To check user is null or not...
        if(isset($user) && $user != null){
            $user->verify_code = rand(100000, 999999);
            $user->verify_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            /*mobile send SMS*/
            $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
            $this->sendSMS($request->mobile,$text);

            $userMobile = Crypt::encrypt($request->mobile);
            Toastr::success('Check number, Please verify otp.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-forgot-pass-verify-OTP-page', ['user_mobile' => $userMobile]);
        }else{
            Toastr::error('Sorry, You have entered wrong number.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get user verify otp page...
    public function getForgotVerifyOtpPage(Request $request, $userMobile)
    {
        $userMobile = Crypt::decrypt($userMobile);
        return view('frontend.auth.verifyOTPForForgotPass', compact('userMobile'));
    }

    //To again resend otp for password change...
    public function resendOtpForPassChange($userMobile)
    {
        //To decript...
        $mobile = Crypt::decrypt($userMobile);
        $user = User::where('mobile',$mobile)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*mobile send SMS*/
        $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMS($mobile,$text);
        //To check user is null or not...
        if(isset($user) && $user != null){
            $userMobile = Crypt::encrypt($mobile);
            Toastr::success('Check number, Please verify otp.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-forgot-pass-verify-OTP-page', ['user_mobile' => $userMobile]);
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
                return view('frontend.auth.changePassword', compact('singleUserData'));
            }
        }

        Toastr::error('Error !! Sorry, OTP not matching.', '', ["progressbar" => true]);
        return redirect()->route('webuser.get-login');
    }

    //To change password update ...
    public function webUserPasswordUpdate(Request $request)
    {
        $request->validate([
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $singleUserData = User::where('mobile', $request->mobile)->first();

        if ($request->new_password == $request->confirm_password) {
            User::find($singleUserData->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Toastr::success('Password Updated, Please Login.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-login');

        }else{
            Toastr::error('Password and Confirm Password do not match.!', '', ["progressbar" => true]);
            return view('frontend.auth.changePassword', compact('singleUserData'));
        }
    }

    //To get all the subject data with class wise...
    public function classWiseSubect($id)
    {
        $subjectData = Subject::orderBy('id','desc')->where('status',true)->get();

        return view('frontend.classWiseSubject', compact('subjectData'));
    }
    
    //To get all the chapter data with subject wise...
    public function subjectWiseChapter($id)
    {
        $chapterData = Chapter::orderBy('id','desc')->where('status',true)->get();

        return view('frontend.subjectWiseChapter', compact('chapterData'));
    }
    
    //To get all the post data with chapter wise...
    public function chapterWisePost($id)
    {
        $postData = Post::orderBy('id','desc')->where('status',true)->get();

        return view('frontend.chapterWisePost', compact('postData'));
    }
    
    //To get all the post details...
    public function postDetails($id)
    {
        $singlePostData = Post::where('id',$id)->where('status',true)->first();
        //To add user post count...
        $this->saveUserPostCount($singlePostData);

        return view('frontend.postDetails', compact('singlePostData'));
    }

    //To add new user post count...
    public function saveUserPostCount($singlePostData)
    {
        $data = UserPostClick::where('classname_id', $singlePostData->classname_id)
        ->where('subject_id', $singlePostData->subject_id)
        ->where('chapter_id', $singlePostData->chapter_id)
        ->where('post_id', $singlePostData->id)->first();

        if(!empty($data)){
            $data->click_count += 1;
            $data->save();
        }else{
            $userPostClick = new UserPostClick();
            $userPostClick->classname_id = $singlePostData->classname_id;
            $userPostClick->subject_id = $singlePostData->subject_id;
            $userPostClick->chapter_id = $singlePostData->chapter_id;
            $userPostClick->post_id = $singlePostData->id;
            $userPostClick->click_count = 1;
            $userPostClick->save();
        }
    }

    //To add post in favourite list...
    public function addRemoveFavouritePost(Request $request)
    {
        //To check user role...
        if(Auth::user()->role == 'user'){
            $postId = $request->post_id;
            $singlePostData = Post::find($postId);
            $data = $request->all();
            $data['classname_id'] = $singlePostData->classname_id;
            $data['subject_id'] = $singlePostData->subject_id;
            $data['chapter_id'] = $singlePostData->chapter_id;
            $data['post_id'] = $postId;
            $data['user_id'] = Auth::user()->id;

            //To check post is already added or not to favourite list...
            $singleFavPostData = Favouritelist::where('classname_id', $singlePostData->classname_id)->where('subject_id', $singlePostData->subject_id)
                                ->where('chapter_id', $singlePostData->chapter_id)->where('post_id', $postId)
                                ->where('user_id', Auth::user()->id)->first();

            if($singleFavPostData != null){
                if($singleFavPostData->delete()){
                    return view('frontend.updatePostFavCount', compact('singlePostData'));
                }
            }else{
                if($result = Favouritelist::create($data)){
                    return view('frontend.updatePostFavCount', compact('singlePostData'));
                }
            }
        }else{
            return response()->json([
                "error" => "You are not loged in like a user..!"
            ]);
        }
    }

    //To get all the favourite post data...
    public function getFavouritePost()
    {
        //To check post from favourite list...
        $favPostIds = Favouritelist::select('post_id')->groupBy('post_id')
                            ->where('user_id', Auth::user()->id)->get()->pluck('post_id')->toArray();
        $postData = Post::orderBy('id','desc')->whereIn('id', $favPostIds)->get();
 
        if($postData != null){
            return view('frontend.getFavouritePostList', compact('postData'));
        }else{
            return view('frontend.getFavouritePostList', compact('postData'));
        }
    }

    //To Send Verify SMS...
    public function sendSMS($contact, $text)
    {
        $url = "https://api.mimsms.com/api/SmsSending/SMS";
        $data = [
            "UserName" => "wbsoft.net@gmail.com",
            "Apikey" => "P0N3239MLBCWT6XGQCJDS3UU7",
            "MobileNumber" => '88'.$contact,
            "CampaignId" => "null",
            "SenderName" => "8809601004746",
            "TransactionType" => "P",
            "Message" => $text
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false) {
            return response()->json(['message' => 'Failed to send SMS', 'error' => curl_error($ch)], 500);
        }
        
        $responseArray = json_decode($response, true);

        if ($responseArray['status'] === 'Success') {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['message' => 'Failed to send SMS', 'response' => $responseArray], 500);
        }
    }
}