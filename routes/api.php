<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\Backend\SupportTicket\SupportTicketController;
use App\Http\Controllers\Backend\Setting\SettingController;
use App\Http\Controllers\Backend\Account\AccountCategoryController;
use App\Http\Controllers\Backend\Account\AccountController;
use App\Http\Controllers\Backend\BlogManage\BlogCategoryController;
use App\Http\Controllers\Backend\BlogManage\BlogController;
use App\Http\Controllers\Backend\UserRole\UserController;
use App\Http\Controllers\Backend\UserRole\RoleController;

Route::group([], function () {

    // Auth Controller register login
    Route::post('/register',        [AuthController::class, 'register'])->name('register');
    Route::post('/reset-password',  [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    Route::post('/login',           [AuthController::class, 'login'])->name('login');
    // To verify OTP...
    Route::post('/otp-verify-code', [AuthController::class, 'verifyOtp'])->name('otp-verify-code');
    Route::post('/resend-otp',      [AuthController::class, 'resendOtp'])->name('resend-otp');
    Route::get('check-otp',         [AuthController::class, 'checkOTP'])->name('check-otp');

    Route::group(['middleware' => 'auth:api'], function () {
        // Logout route
        Route::get('logout',                                [AuthController::class, 'logout'])->name('logout');
        Route::get('admin-dashboard', 	                    [App\Http\Controllers\API\Backend\AdminController::class, 'adminDashboard'])->name('admin-dashboard');
        Route::get('/package-renew', 						[App\Http\Controllers\API\Backend\AdminController::class, 'renewPage'])->name('package-renew');
        Route::post('save-package-renew', 					[App\Http\Controllers\API\Backend\AdminController::class, 'saveRenewData'])->name('save-package-renew');
	
	    Route::middleware(['check.package'])->group(function () {


            //Account Management...
            //Account Category
            Route::resource('account-category', AccountCategoryController::class);
            Route::get('account-category-active/{id}', 		[AccountCategoryController::class, 'active'])->name('account-category-active');
            Route::get('account-category-inactive/{id}', 	[AccountCategoryController::class, 'inactive'])->name('account-category-inactive');
            Route::get('account-category-delete/{id}', 		[AccountCategoryController::class, 'destroy'])->name('account-category-delete');
            // Account
            Route::resource('account',         AccountController::class);
            Route::get('account-active/{id}',     [AccountController::class, 'active'])->name('account-active');
            Route::get('account-inactive/{id}',   [AccountController::class, 'inactive'])->name('account-inactive');
            Route::get('account-delete/{id}',     [AccountController::class, 'destroy'])->name('account-delete');
            Route::get('account-profile/{id}',    [AccountController::class, 'accountProfile'])->name('account-profile');
            Route::post('add-transfer-amount', 	  [AccountController::class, 'addTranserData'])->name('add-transfer-amount');

            //Support Module...
            Route::resource('ticket-support', 		SupportTicketController::class);
            Route::get('ticket-support-close/{id}', 	[SupportTicketController::class, 'supportTicketClose'])->name('ticket-support-close');
            Route::get('ticket-support-details/{id}', 	[SupportTicketController::class, 'supportTicketDetail'])->name('ticket-support-details');
            Route::post('ticket-support-reply/{id}', 	[SupportTicketController::class, 'supportTicketRepply'])->name('ticket-support-reply');

            //For Blog Controller Section...
            //Blog Category
            Route::resource('blog-category', BlogCategoryController::class);
            Route::get('blog-category-delete/{id}', 			[BlogCategoryController::class, 'destroy'])->name('blog-category-delete');
            Route::get('blog-category-active/{id}', 			[BlogCategoryController::class, 'active'])->name('blog-category-active');
            Route::get('blog-category-inactive/{id}', 			[BlogCategoryController::class, 'inactive'])->name('blog-category-inactive');
            //Blog
            Route::resource('blog', BlogController::class);
            Route::get('blog-active/{id}', 						[BlogController::class, 'blogActive'])->name('blog-active');
            Route::get('blog-inactive/{id}', 					[BlogController::class, 'blogInactive'])->name('blog-inactive');
            Route::get('blog-delete/{id}', 						[BlogController::class, 'destroy'])->name('blog-delete');
            Route::post('upload-image-for-blog',				[BlogController::class, 'uploadImage'])->name('upload-image-for-blog');

            //User & Role Controller...
            //User
            Route::resource('users', UserController::class);
            Route::get('users-active/{id}', 	[UserController::class, 'active'])->name('users-active');
            Route::get('users-inactive/{id}', 	[UserController::class, 'inactive'])->name('users-inactive');
            Route::get('users-delete/{id}',     [UserController::class, 'destroy'])->name('users-delete');
            //Role
            Route::resource('roles', RoleController::class);
            Route::get('roles-create',          [RoleController::class, 'create'])->name('roles-create');
            Route::get('roles-edit/{id}',       [RoleController::class, 'edit'])->name('roles-create');

            //Setting & Other Route is Here...
            Route::get('/setting', 								[SettingController::class, 'index'])->name('setting');
            Route::post('/logo/save', 							[SettingController::class, 'store'])->name('logo.store');
            Route::post('/logo/update/{id}', 					[SettingController::class, 'update'])->name('logo.update');
            Route::post('/footer-text-submit/{id}', 			[SettingController::class, 'footerTextSubmit'])->name('footer-text-submit');
            Route::post('/invoice-logo/save', 					[SettingController::class, 'invoiceLogoSave'])->name('invoice-logo.store');
            Route::post('/invoice-logo/update/{id}', 			[SettingController::class, 'invoiceLogoUpdate'])->name('invoice-logo.update');
            //Contact Form Data...
            Route::get('/contact-form-data', 					[SettingController::class, 'getContactFormData'])->name('contact-form-data');
            Route::get('/contact-form-solved/{id}', 			[SettingController::class, 'getContactFormSolved'])->name('contact-form-solved');
            Route::get('/contact-form-unsolved/{id}', 			[SettingController::class, 'getContactFormUnsolved'])->name('contact-form-unsolved');

            // Profile update routes
            Route::get('/profile',                  [ProfileController::class, 'getUserData'])->name('profile');
            Route::post('/profile/update',          [ProfileController::class, 'profileUpdate'])->name('profile.update');
            Route::post('/profile/mobile/update',   [ProfileController::class, 'profileMobileUpdate'])->name('profile.mobile.update');
            Route::post('/profile/mobile/verify',   [ProfileController::class, 'profileMobileVerifyOtp'])->name('profile.mobile.update');
            Route::post('/profile/password/update', [ProfileController::class, 'securityUpdate'])->name('profile.password.update');

            
        });
    });
});

// Get authenticated user data
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
