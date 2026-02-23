<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebUser\WebUserController;
use App\Http\Controllers\WebUser\WebHomeController;
use App\Http\Controllers\WebUser\ProfileController;



//To get all the auth routes list of web user...
Route::middleware(['guest:webuser'])->prefix('student')->as('webuser.')->group(function () {
  Route::get('register',    [WebUserController::class, 'webUserRegisterPage'])->name('get-register');
  Route::post('register',   [WebUserController::class, 'webUserRegister'])->name('post-register');
  Route::get('verify-OTP',  [WebUserController::class, 'webUserLoginPage'])->name('get-verify-OTP');
  Route::get('verify-OTP/{user_email}',  [WebUserController::class, 'getVerifyOtpPage'])->name('get-verify-OTP-page');
  Route::post('verify-OTP', [WebUserController::class, 'webUserVerifyOtp'])->name('post-verify-OTP');
  Route::get('resend-OTP/{user_email}',   [WebUserController::class, 'resendOTP'])->name('resend-OTP');


  Route::get('login',       [WebUserController::class, 'webUserLoginPage'])->name('get-login');
  Route::post('login',      [WebUserController::class, 'webUserogin'])->name('post-login');
  Route::get('password-forgot',                      [WebUserController::class, 'forgotPassword'])->name('password-forgot');
  Route::post('password-forgot-OTP-sent',            [WebUserController::class, 'forgotPasswordOTPSent'])->name('password-forgot-OTP-sent');
  Route::get('forgot-pass-verify-OTP/{user_mobile}', [WebUserController::class, 'getForgotVerifyOtpPage'])->name('get-forgot-pass-verify-OTP-page');
  Route::post('password-change',                     [WebUserController::class, 'forgotPassVerifyOtp'])->name('post-forgot-pass-verify-OTP');
  Route::get('resend-OTP-for-password-change/{user_mobile}',[WebUserController::class, 'resendOtpForPassChange'])->name('resend-OTP-for-password-change');
  Route::post('/password-update',                    [WebUserController::class, 'webUserPasswordUpdate'])->name('get-password-update');
});

//To get all the routes list of web user...
Route::middleware(['auth:webuser'])->prefix('student')->as('webuser.')->group(function () {
    Route::get('dashboard',                 [WebHomeController::class, 'userDashboard'])->name('dashboard');
    Route::get('logout',                    [WebHomeController::class, 'webUserLogout'])->name('logout');

    //Personal Dashboard
    Route::get('personal-dashboard', 		    [ProfileController::class, 'personalDashboard'])->name('personal-dashboard');
    Route::post('get-order-products-data', 	[ProfileController::class, 'getOrderProductsData'])->name('get-order-products-data');
    Route::post('profile-update', 	        [ProfileController::class, 'userProfileUpdate'])->name('profile-update');
    Route::post('password-update', 	        [ProfileController::class, 'userProfilePasswordUpdate'])->name('password-update');

});