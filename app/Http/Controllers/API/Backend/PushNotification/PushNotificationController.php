<?php

namespace App\Http\Controllers\Backend\PushNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\SMSTemplate;
use App\Models\PushNotification;
use Auth;
use Carbon\Carbon;
use App\Helpers\CurrentUser;
use Spatie\Permission\Models\Permission;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('push-notification-list', 'web')) {
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
        $pushNotificationData = PushNotification::where('user_id', $userId)->where('sending_date', '!=', null)->orderBy('id','desc')->paginate(10);
        $pushNotificationCount = PushNotification::where('user_id', $userId)->where('sending_date', '!=', null)->orderBy('id','desc')->count();
        $smsTemplateMData = SMSTemplate::orderBy('id','DESC')->where('user_id', $userId)->get();
        if(Auth::user()->role == 'superadmin'){
            $roleData = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roleData = Role::whereNotIn('name', ['superadmin','admin'])->where('admin_id', $userIdFCU)->get();
        }else{
            $userRoleName = Auth::user()->role;
            $roleData = Role::whereNotIn('name', ['superadmin','admin',$userRoleName])->where('admin_id', $userIdFCU)->get();
        }

        // API response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded SMS data.',
                'status_code' => 200,
                'pushNotificationData' => $pushNotificationData,
                'pushNotificationCount' => $pushNotificationCount,
                'smsTemplateMData' => $smsTemplateMData,
                'roleData' => $roleData,
            ], 200);
        }

        return view('backend.pushNotification.index', compact(
            'pushNotificationData',
            'pushNotificationCount',
            'smsTemplateMData',
            'roleData',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if (!auth()->user()->can('push-notification-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'sending_date' => 'required',
            'sending_time' => 'required',
            'role_id' => 'required',
            'sms_template_id'=> 'required',
        ]);

        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['is_scheduler'] = true;
        $data['sending_date'] = Carbon::parse($request->sending_date)->format('Y-m-d');
        $data['sending_time'] = Carbon::parse($request->sending_time)->format('H:i');

        if(PushNotification::create($data)){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Notification added successfully.',
                    'status_code' => 200,
                ], 200);
            }

            Toastr::success('Notification added successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Something is wrong.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if (!auth()->user()->can('push-notification-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'sending_date' => 'required',
            'sending_time' => 'required',
            'role_id' => 'required',
            'sms_template_id'=> 'required',
        ]);

        $userId = CurrentUser::getOwnerId();
        $singlePushNotification = PushNotification::where('id', $id)->first();
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['is_scheduler'] = true;
        $data['sending_date'] = Carbon::parse($request->sending_date)->format('Y-m-d');
        $data['sending_time'] = Carbon::parse($request->sending_time)->format('H:i');

        if($singlePushNotification->update($data)){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Notification updated successfully.',
                    'status_code' => 200,
                ], 200);
            }

            Toastr::success('Notification updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Something is wrong.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //To check user permission...
        if (!auth()->user()->can('push-notification-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singlePushNotification = PushNotification::findOrFail($id);
        if($singlePushNotification->delete()){
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   =>  'Notification deleted successfully.',
                    'status_code' => 200,
                ], 200);
            }
            
            Toastr::success('Notification deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            //To API response...
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry!! Something is wrong.',
                    'status_code' => 500,
                ], 500);
            }

            Toastr::error('Sorry!! Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }


    public function pushNotificationSend(Request $request, $id)
    {
        //To get single push notification...
        $pushNotification = PushNotification::where('id', $id)->first();

        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $serverKey = 'AAAAcbRxdOs:APA91bGjVUnfD9AtXHzdUT4aJJE-hnEogDPGbVYrnVk1nvci8aFwzrxzHpeq_EUxqs9VBVt4MaSrFLcsqmNZucO8qUAroy5ynmJg2x9WylJ34jhAmPtB4mjxZP_60pKrJK48sR8xB5KQ'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $pushNotification->notification_title,
                "body" => $pushNotification->notification_message,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            return response()->json([
                'error' => 'Somethig is wrong.!'
            ]);
        }else{
            //To update push notification ststaus....
            $pushNotification->status = true;
            $pushNotification->save();
            curl_close($ch);
            return redirect()->route('push-notification.index');
        }   
    }



    // Direct Send -----------------------------------

    public function pushNotificationDirect()
    {
        $data = PushNotification::where('sending_date',null)->orderBy('id','desc')->paginate(10);
        return view('admin.directSentNotification.index',compact('data'));
    }

    public function pushNotificationDirectCreate()
    {
        return view('admin.directSentNotification.create');
    }

    public function pushNotificationDirectStore(Request $request)
    {    
        $request->validate([
            'notification_title' => 'required',
            'notification_message' => 'required'
        ]);

        $data = $request->all();
        $data['is_scheduler'] = false;
        if($data = PushNotification::create($data)){

            $pushNotification = PushNotification::where('id', $data->id)->first();
            
            if (isset($pushNotification) && $pushNotification !=  null) {

                $title = $pushNotification->notification_title;
                $message = $pushNotification->notification_message;

                // For Notification message...
                $tagRemovalNotificationMessage = strip_tags($message);
                $originalNotificationMessage = preg_replace("/\s|&nbsp;/"," ",$tagRemovalNotificationMessage);
                
                //To send push notification...
                PushNotification::pushNotificationSend($title, $originalNotificationMessage);

                //To update push notification ststaus....
                $pushNotification->status = true;
                $pushNotification->save();

                Toastr::success('PushNotification sent successfull.', '', ["progressbar" => true]);
                return redirect()->route('push-notification-direct');
            }else{
                $pushNotification->delete();
                return redirect()->back();
            }   

        }else{
            Toastr::error('Something is wrong.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    public function pushNotificationDirectDelete($id)
    {
        $pushNotification = PushNotification::where('id', $id)->first();
        if($pushNotification->delete()){
            Toastr::success('PushNotification deleted successfull.', '', ["progressbar" => true]);
            return redirect()->route('push-notification-direct');
        }else{
            Toastr::error('Something is wrong.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}