<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Validator;
use Session;
use Hash;
use Auth;
use Image;
use DB;

class ProfileController extends Controller
{
    //To get single user data...
    public function getUserData(Request $request)
    {
        $userData = User::where('id', Auth::user()->id)->first();
        //To get role data...
        $singleRoleData = Role::where('name', $userData->role)->first();
        $rolePermissionIds = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$singleRoleData->id)
            ->pluck('role_has_permissions.permission_id')
            ->toArray();
        $userPermissionData = Permission::whereIn('id', $rolePermissionIds)->get();
        
        if(!empty($userData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'userData'   =>  $userData,
                'userPermissionData' => $userPermissionData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To update user basic profile data...
    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'address' => 'nullable',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        $userData = User::where('id', Auth::user()->id)->first();

        //To check logo image...
        foreach (['image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/user_img');
            }
        }
        
        if($userData->update($data)){
            return response()->json([
                'message' => 'Profile updated successfully.',
                'userData'   =>  $userData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To file upload...
    private function uploadFile($file, $path)
    {
        $fileName = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = "$path/$fileName";
        Storage::disk('public')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    //To update user mobile...
    public function profileMobileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_mobile' => 'required|digits:11|unique:users,login_mobile,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $userData = User::find(Auth::id());

        $userData->verify_code = rand(100000, 999999);
        $userData->verify_expires_at = Carbon::now()->addMinutes(10);

        $mobile = $request->login_mobile;
        $loginMobile = $request->login_mobile;
        Session::put('selectedMobile', $mobile);

        $text = 'Congratulations! Your Login OTP code is: '
                . $userData->verify_code .
                ' Send By WB SOFTWARES. Please do NOT share your OTP or PIN with others.';

        $this->sendSMS($mobile, $text);

        if ($userData->save()) {
            return response()->json([
                'message'     => 'OTP has sent to your mobile, Please verify your mobile.',
                'verifyOtp'   => $userData->verify_code,
                'login_mobile'=> $loginMobile,
                'mobile'      => $mobile,
                'status_code' => 201
            ], 201);
        }

        return response()->json([
            'message' => 'Something is wrong.',
            'status_code' => 500
        ], 500);
    }



    //To verify mobile otp....
    public function profileMobileVerifyOtp(Request $request)
    {
        $verify_code_mas = User::where('verify_code', $request->verify_code)->first();
        $mobileNum = $request->mobile;
        if(isset($verify_code_mas) && $verify_code_mas != null){
            if( $verify_code_mas->verify_expires_at < (Carbon::now())){
                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->save();
                
                    return response()->json([
                        'message'   =>  'Your Verify Opt has expired. Please Resend Code.',
                        'status_code'   => 201
                    ], 500);
                
                }else{
                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->mobile = $mobileNum;
                    $verify_code_mas->status = 1;
                    $verify_code_mas->save();

                    Session::forget('selectedMobile');
                    return response()->json([
                        'message'   =>  'You are now verified.',
                        'status_code'   => 201
                    ], 201);
                }
        }else{
             return response()->json([
                'message'   =>  'Your Opt you have entered does not match',
                'status_code'   => 500
            ], 500);
        }
    }

    //To update user password...
    public function securityUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);

        $currentUser = User::where('id', Auth::user()->id)->first();
        if (Hash::check($request->old_password,$currentUser->password)) {
            if ($request->password == $request->password_confirmation) {
                User::find($currentUser->id)->update([
                    'password' => Hash::make($request->password)
                ]);

                // $this->userLogout($currentUser->id);
                return response()->json([
                    'message'   =>  'Your password has changed successfully',
                    'status_code'   => 201
                ], 201);
            }else{
                return response()->json([
                    'message'   =>  'Password and Confirm Password do not match.',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Old Password do not match.',
                'status_code'   => 500
            ], 500);
        }
    }


    //To logout...
    public function userLogout($userId)
    {
        $user = User::where('id', $userId)->first();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully done.',
            'status_code'   => 201
        ]);
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
}
