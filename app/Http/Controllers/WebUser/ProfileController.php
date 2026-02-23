<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Helpers\CheckCurrencyType;
use App\Models\UserCurrencyType;
use App\Models\ProductOrder;
use App\Models\OrderProductList;
use App\Models\User;
use Carbon\Carbon;
use Image;
use Hash;
use Auth;

class ProfileController extends Controller
{
    //To get login page...
    public function userLogin()
    {
        return view('frontend.auth.login');
    }
    
    //To get personal dashboard data...
    public function personalDashboard(Request $request)
    {
        $isBDT = CheckCurrencyType::getCurrencyTypeData($request);
        $userIp = $request->ip();
        if(isset(Auth::user()->id)){
            $productOrderData = ProductOrder::where('user_id', Auth::user()->id)->orWhere('user_ip', $userIp)->paginate(10);
            $allProductOrderCount = ProductOrder::where('user_id', Auth::user()->id)->orWhere('user_ip', $userIp)->count();
            $productOrderId = ProductOrder::where('user_id', Auth::user()->id)->orWhere('user_ip', $userIp)->select('id')->pluck('id');
            $totalProductOrderPrice = ProductOrder::where('user_id', Auth::user()->id)->orWhere('user_ip', $userIp)->sum('total_product_price');
            $totalProductOrderDuePrice = ProductOrder::where('user_id', Auth::user()->id)->orWhere('user_ip', $userIp)->sum('total_due_amount');
            $totalOrderProductQty = OrderProductList::whereIn('product_order_id', $productOrderId)->sum('product_qty');
        }

        //To check user login or not...
        if(isset(Auth::user()->id) && Auth::user()->role == 'user'){
            return view('frontend.profile.personalDashboard', compact('productOrderData','allProductOrderCount','totalProductOrderPrice'
                    ,'totalProductOrderDuePrice','totalOrderProductQty'));
        }else{
            return view('frontend.profile.personalDashboard');
        }
    }
    
    //To get order products data...
    public function getOrderProductsData(Request $request)
    {
        $request->validate([
            'order_id'=> 'required',
            'phone'=> 'required',
        ]);

        //To get singleorder data...
        $isBDT = CheckCurrencyType::getCurrencyTypeData($request);
        $singlePOData = ProductOrder::where('order_id', $request->order_id)->first();
        
        if(isset($singlePOData) && $singlePOData != null){
            $productOrderListData = OrderProductList::where('product_order_id', $singlePOData->id)->get();
            return view('frontend.profile.getOrderProductsData', compact('singlePOData','productOrderListData','isBDT'));
        }else{
            return response()->json([
                'error' => 'Sorry, You have no data.!',
            ]);
        }
    }
    
    //To user profile update...
    public function userProfileUpdate(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => 'required',
            'address' => 'required',
            'email' => 'nullable|unique:users,email,'.Auth::user()->id,
            'mobile' => 'required|min:11|max:11|unique:users,mobile,'.Auth::user()->id,
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        $data = $request->all();
        $singleUserData = User::find(Auth::user()->id);

        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('uploads/user_img/');
            if(file_exists($destinationPath.$singleUserData->image)){
                if($singleUserData->image != ''){
                    unlink($destinationPath.$singleUserData->image);

                    //For thumbnail...
                    $destinationPath = public_path('uploads/user_img/thumbnail/');
                    unlink($destinationPath.$singleUserData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/user_img/');

            //For large size image...
            $destinationPath = public_path('uploads/user_img/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('uploads/user_img/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);

            $data['image'] = $fileName;
        }

        if($singleUserData->update($data)){
            Toastr::success('Profile updated successfull.', '', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is error there.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To update user password...
    public function userProfilePasswordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $currentUserData = Auth()->user();

        if (Hash::check($request->old_password,$currentUserData->password)) {
            if ($request->new_password == $request->confirm_password) {

                //To fetch single user...
                $singleUser = User::find($currentUserData->id);
                $singleUser->password = Hash::make($request->new_password);

                if($singleUser->save()){
                    Toastr::success('Password updated successfull.', '', ["progressbar" => true]);
                    return redirect()->back();
                }else{
                    Toastr::error('Something is error there.!', '', ["progressbar" => true]);
                    return redirect()->back();
                }
            }else{
                Toastr::error('Password and confirm password do not match.!', '', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Old password do not match.!', '', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To user logout...
    public function webUserLogout()
    {
        Auth::guard('webuser')->logout();

        Toastr::success('Success, Your are now log out user.', '', ["progressbar" => true]);
        return Redirect()->route('webuser.get-login');
    }
    
}
