<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Models\IdCardSetting;
use App\Models\User;
use Carbon\Carbon;
use Session;

class AuthController extends Controller
{
    //To User Register...
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'organization_name' => 'required',
            'login_mobile' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }
        
        $data = $request->all();
        $data['role'] = strtolower('admin');
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);
        $data['password'] = Hash::make($request->password);
        $data['mobile'] = $request->login_mobile;

        $newUser = User::create($data);
        $inst = new IdCardSetting();
        $inst->organization_name = $request->organization_name;
        $inst->user_id = $newUser->id;
        $inst->save();

        if ($newUser) {
            $userRole = Role::where('name', 'admin')->pluck('id');
            $newUser->assignRole($userRole);
            $accessToken = $newUser->createToken('Testpaper2022')->accessToken;

            //To send otp...
            $text = 'Congratulations! Your Login OTP code is: '.$newUser->verify_code.' Send By WB SOFTWARES. Please do NOT share your OTP or PIN with others.';
            $this->sendSMS($newUser->mobile, $text);

            Session::put('user_id', $newUser->id);

            return response()->json([
                'message' => 'Success !! Registration completed, please verify now.',
                'verifyCode' => $newUser->verify_code,
                'userData' => $newUser,
            ]);
        }

        return response()->json([
            'message' => 'Sorry !! Something went wrong.'
        ], 500);
    }

    //To User Login...
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'mobile' => 'nullable',
            'password' => 'required',
        ]);

        $userQuery = User::query();
        if ($request->email) {
            $userQuery->where('email', $request->email);
        } elseif ($request->mobile) {
            $userQuery->where('mobile', $request->mobile);
        }
        
        $user = $userQuery->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found, Please register first.',
            ], 500);
        }
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Sorry !! Password not mathcing.',
            ], 500);
        }

        //To check user status...
        if ($user->status != 1) {
            return response()->json([
                'message' => 'Account not verified. Please contact support.',
            ], 403);
        }

        $accessToken = $user->createToken('SkinCare2023')->accessToken;

        // Return success response
        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $accessToken,
            'userData' => $user,
        ], 200);
    }


    //To Reset Password...
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required'],
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        $userData = User::where('mobile', $request->mobile)->first();
        if(isset($userData)){
            $mobileNumber = $request->mobile;
            $text = 'Congratulations! Your Login OTP code is: '.$data['verify_code'].' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
            $this->sendSMS($mobileNumber,$text);
            
            if($userData->update($data)){
                return response()->json([
                    'message'   =>  'OTP has sent to your mobile, Please verify your mobile.',
                    'verifyOtp'   =>  $userData->verify_code,
                    'mobile'   =>  $userData->mobile
                ], 201);
            }else{
                return response()->json([
                    'message'   =>  'The opt has been sent not again!.'
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!'
            ], 500);
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
                $data = [
                    'message' => 'Your password changed successfully, Please Login now.',
                    'userData' => $user,
                ];
        
                return response()->json($data);
            }else{
                return response()->json([
                    'message'   =>  'Sorry, Password not matching.!'
                ], 500);
            }
        }
        else{
            return response()->json([
				'message'   =>  'Sorry, Something is wrong.!'
			], 500);
        }
    }


    //To logout...
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout successfully done.'
        ]);
    }


    //To verify otp...
    public function verifyOtp(Request $request)
    {
        $verify_code_mas = User::where('mobile', $request->mobile)->first();
        if($verify_code_mas){
            if($verify_code_mas->verify_code == $request->verify_code){
                if( $verify_code_mas->verify_expires_at < (Carbon::now())){

                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->save();
                
                    return response()->json([
                        'message'   =>  'OTP verfication timeout, Please resend OTP.!'
                    ], 500);
                
                }else{

                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->status = 1;
                    $verify_code_mas->save();
                    
                    return response()->json([
                        'message'   =>  'You are now verified, Please update password.',
                    ], 201);
                }
            }else{
                return response()->json([
                    'message'   =>  'Sorry, OTP not matching.!'
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!'
            ], 500);
        }
       
    }

    //To resend otp...
    public function resendOtp(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();

        if($user){
            $user->verify_code = rand(100000, 999999);
            $user->verify_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            /*mobile send SMS*/
            $text = 'Congratulations! Your Verify Opt. '. $user->verify_code;
            $this->sendSMS($user->mobile,$text);
            
            return response()->json([
                'message'   =>  'OTP has sent to you, Please verify.',
                'verifyCode' => $user->verify_code,
                'data'   =>  $user
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!'
            ], 500);
        }

    }

    //To Send Verify SMS...
    public function checkOTP()
    {
        $mobile = '01733165883';
        $message = 'This is test message.';
        $data = [
            "UserName" => "wbsoft.net@gmail.com",
            "Apikey" => "PMHJ4UCXBP79E2E8AMYVUF3KR",
            "MobileNumber" => '88'.$mobile,
            "CampaignId" => "null",
            "SenderName" => "8809601004746",
            "TransactionType" => "T",
            "Message" => $message,
        ];

        $ch = curl_init("https://api.mimsms.com/api/SmsSending/SMS");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_exec($ch);
        curl_close($ch);

        return response()->json(['message' => 'SMS sent successfully']);
    }
    
    //To Send Verify SMS...
    public function sendSMS($contact, $message)
    {
        $url = "https://api.mimsms.com/api/SmsSending/SMS";
        $data = [
            "UserName" => "wbsoft.net@gmail.com",
            "Apikey" => "PMHJ4UCXBP79E2E8AMYVUF3KR",
            "MobileNumber" => '88'.$contact,
            "CampaignId" => "null",
            "SenderName" => "8809601004746",
            "TransactionType" => "T",
            "Message" => $message,
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

    protected function guard()
    {
        return Auth::guard();
    }
}
