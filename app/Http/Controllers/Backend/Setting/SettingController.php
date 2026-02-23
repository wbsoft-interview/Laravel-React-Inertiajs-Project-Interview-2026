<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\Models\Logo;
use App\Models\InvoiceLogo;
use App\Models\FooterText;
use App\Models\FrontendContactForm;
use App\Models\ActivityLog;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Image;
use Auth;

class SettingController extends Controller
{
    public function index(Request $request)
    {	
        //To check user permission...
        if (!auth()->user()->can('general-setting-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$logo = Logo::where('user_id',$userId)->first();
        $footerText = FooterText::where('user_id',$userId)->first();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'getSoftwareLogo'   =>  $logo,
                'getFooterText'   =>  $footerText,
            ], 200);
        }
      
        return view('backend.setting.index',compact('logo','footerText'));
    }

    //To get user activity list...
    public function getUserActivity(Request $request)
    {	
        //To check user permission...
        if (!auth()->user()->can('general-setting-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$activityLogData = ActivityLog::orderBy('id','desc')->where('user_id',$userId)->paginate(10);
    	$allActivityLogCount = ActivityLog::orderBy('id','desc')->where('user_id',$userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully loaded data.',
                'status_code' => 200,
                'activityLogData'   =>  $activityLogData,
                'allActivityLogCount'   =>  $allActivityLogCount
            ], 200);
        }

    	return view('backend.setting.getUserActivityLogData.index',compact('activityLogData','allActivityLogCount'));
    }

    public function store(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('general-setting-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

    	$request->validate([
            'logo_width'=> 'required',
            'logo_height'=> 'required',
            'logo_image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check logo image...
        foreach (['logo_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/logoImg');
            }
        }

        if(Logo::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Logo created successfully.',
                    'status_code' => 200,
                    'logoData' => $data
                ], 200);
            }
            
            Toastr::success('Logo created successfully.', 'Success', ["progressbar" => true]);
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

    //To file upload...
    private function uploadFile($file, $path)
    {
        $fileName = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = "$path/$fileName";
        Storage::disk('public')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    public function update(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('general-setting-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

    	$request->validate([
            'logo_width'=> 'required',
            'logo_height'=> 'required',
            'logo_image'=> 'nullable|image|mimes:jpg,jpeg,png,gif,svg',
        ]);

        $data = $request->all();

        //To get previous logo data...
        $oldLogo = Logo::where('id', $id)->first();
        
        //To check logo image...
        foreach (['logo_image'] as $field) {
            if ($request->hasFile($field)) {
                if (!empty($oldLogo->$field)) {
                    $filePath = 'uploads/logoImg/' . $oldLogo->$field;
                    Storage::disk('public')->delete($filePath);
                }
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/logoImg');
            }
        }

        if($oldLogo->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Logo updated successfully.',
                    'status_code' => 200,
                    'logoData' => $oldLogo
                ], 200);
            }
            
            Toastr::success('Logo updated successfully.', 'Success', ["progressbar" => true]);
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


    //footer submit...........
    public function footerTextSubmit(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('general-setting-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        if ($request->footer_id) {
            $data = FooterText::where('user_id', $userId)
                            ->where('id', $request->footer_id)
                            ->firstOrFail();
        } 
        else {
            $data = new FooterText();
            $data->user_id = $userId;
        }

        $data->text = $request->text;

        $tagRemovalFT = strip_tags($request->text);
        $data->solid_text = preg_replace("/\s|&nbsp;/", " ", $tagRemovalFT);

        if ($data->save()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Footer setting saved successfully.',
                    'status_code' => 200,
                    'footerTextData' => $data
                ], 200);
            }

            Toastr::success('Footer setting saved successfully.', 'Success', ["progressbar" => true]);
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
