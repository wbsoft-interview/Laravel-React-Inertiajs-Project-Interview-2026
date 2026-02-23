<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use DB;
use App\Models\User;
use App\Models\Branch;
use Auth;
use Hash;
use Carbon\Carbon;
use Validator;
use Session;
use Image;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Profile/Index');
    }

    //To update user profile data...
    public function adminUpdate(Request $request, $id)
    {
    	$request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|unique:users,email,'.Auth::user()->id,
            'mobile' => 'nullable|min:11|max:11|unique:users,mobile,'.Auth::user()->id,
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        $data = $request->all();
        $User = User::find($id);
        $data['login_mobile'] = $request->mobile;

        //To check logo image...
        foreach (['image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/user_img');
            }else{
                $data[$field] = $User->image;
            }
        }

        try {
            $User->update($data);
            
            return redirect()
                ->back()
                ->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors([
                    'general' => 'Something went wrong. Please try again.',
                ]);
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

    //To get user security page for password change...
    public function adminSecurity()
    {
    	return view('backend.profile.security');
    }

    //To update user password...
    public function adminSecurityUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $current_user = Auth()->user();

        if (Hash::check($request->old_password,$current_user->password)) {

            if ($request->new_password == $request->confirm_password) {

                //To fetch single user...
                $singleUser = User::find($current_user->id);
                $singleUser->password = Hash::make($request->new_password);

                if($singleUser->save()){
                    return redirect()
                    ->back()
                    ->with('success', 'Password updated successfully');
                }else{
                    return redirect()
                    ->back()
                    ->with('error', 'Something is error there.');
                }
            }else{
                return redirect()
                    ->back()
                    ->with('error', 'Password and confirm password do not match.');
            }

        }else{
            return redirect()
                    ->back()
                    ->with('error', 'Old password do not match.');
        }
    }

}
