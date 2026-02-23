<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Backend\Setting\GalleryController;
use App\Http\Controllers\Backend\Setting\ZoneController;
use App\Http\Controllers\Backend\UserRole\RoleController;
use App\Http\Controllers\Backend\UserRole\UserController;
use App\Http\Controllers\Backend\BlogManage\BlogCategoryController;
use App\Http\Controllers\Backend\BlogManage\BlogController;
use App\Http\Controllers\Backend\Account\AccountCategoryController;
use App\Http\Controllers\Backend\Account\AccountController;
use App\Http\Controllers\Backend\PackageManage\PackageCategoryController;
use App\Http\Controllers\Backend\PackageManage\PackageController;
use App\Http\Controllers\Backend\SMSModule\SMSTemplateController;
use App\Http\Controllers\Backend\SMSModule\SMSDashboardController;
use App\Http\Controllers\Backend\SMSModule\NoticeSMSController;
use App\Http\Controllers\Backend\PushNotification\PushNotificationController;
use App\Http\Controllers\Backend\ExpenseManagement\PayeeController;
use App\Http\Controllers\Backend\ExpenseManagement\ExpenseCategoryController;
use App\Http\Controllers\Backend\ExpenseManagement\ExpenseController;
use App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController;
use App\Http\Controllers\Backend\IncomeManagement\IncomeController;
use App\Http\Controllers\Backend\IncomeManagement\IncomeCategoryController;
use App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController;
use App\Http\Controllers\Backend\IncomeManagement\ReceiverController;
use App\Http\Controllers\Backend\SupportTicket\SupportTicketController;
use App\Http\Controllers\Backend\DocumentationManage\DocumentationCategoryController;
use App\Http\Controllers\Backend\DocumentationManage\DocumentationController;
use App\Http\Controllers\Backend\DocumentationManage\DocumentationTagController;





Route::get('/', 					[App\Http\Controllers\Frontend\FrontendController::class, 'index'])->name('welcome');
Route::get('/access-denied', 		[App\Http\Controllers\Frontend\FrontendController::class, 'accessDenied'])->name('error.accessDenied');
Auth::routes();
Route::get('/login',       								[App\Http\Controllers\Auth\LoginController::class, 'getLoginPageForAdmin'])->name('login');
Route::get('/admin-login',       						[App\Http\Controllers\Auth\LoginController::class, 'getLoginPage'])->name('admin.login');
Route::post('/admin-login',   							[App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
//Forgot password...
Route::get('password-forgot',                      [App\Http\Controllers\Auth\LoginController::class, 'forgotPassword'])->name('password-forgot');
Route::post('password-forgot-OTP-sent',            [App\Http\Controllers\Auth\LoginController::class, 'forgotPasswordOTPSent'])->name('password-forgot-OTP-sent');
Route::get('forgot-pass-verify-OTP/{user_email}', [App\Http\Controllers\Auth\LoginController::class, 'getForgotVerifyOtpPage'])->name('get-forgot-pass-verify-OTP-page');
Route::post('password-change',                     [App\Http\Controllers\Auth\LoginController::class, 'forgotPassVerifyOtp'])->name('post-forgot-pass-verify-OTP');
Route::get('resend-OTP-for-password-change/{user_email}',[App\Http\Controllers\Auth\LoginController::class, 'resendOtpForPassChange'])->name('resend-OTP-for-password-change');
Route::post('get-password-update',                    [App\Http\Controllers\Auth\LoginController::class, 'webUserPasswordUpdate'])->name('get-password-update');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
	Route::get('/logout', 								[App\Http\Controllers\Backend\AdminController::class, 'adminLogout'])->name('admin.logout');
	Route::get('/package-renew', 						[App\Http\Controllers\Backend\AdminController::class, 'renewPage'])->name('package-renew');
	Route::post('save-package-renew', 					[App\Http\Controllers\Backend\AdminController::class, 'saveRenewData'])->name('save-package-renew');
	Route::get('/bkash/callback',                     	[App\Http\Controllers\Backend\AdminController::class, 'bkashCallback'])->name('bkash.callback');
  	Route::get('view-package-purchase-invoice/{ticketing_id}', [App\Http\Controllers\Backend\AdminController::class, 'showInvoice'])->name('view-package-purchase-invoice');
	
	Route::middleware(['check.package'])->group(function () {
		Route::get('/admin/dashboard', 						[App\Http\Controllers\Backend\AdminController::class, 'adminDashboard'])->name('admin.home');
		Route::get('/admin/dashboard', 						[App\Http\Controllers\Backend\AdminController::class, 'adminDashboard'])->name('admin.dashboard');
		Route::get('/najmun', 								[App\Http\Controllers\Backend\AdminController::class, 'najmun'])->name('najmun');

		//Admin Profile Route is Here...
		Route::get('/admin/profile', 						[App\Http\Controllers\Backend\ProfileController::class, 'index'])->name('admin.profile');
		Route::get('/admin/security', 						[App\Http\Controllers\Backend\ProfileController::class, 'adminSecurity'])->name('admin.security');
		Route::post('/admin/profile/update/{id}', 			[App\Http\Controllers\Backend\ProfileController::class, 'adminUpdate'])->name('admin.profile.update');
		Route::post('/admin/security/update', 				[App\Http\Controllers\Backend\ProfileController::class, 'adminSecurityUpdate'])->name('admin.security.update');

		//For Account Controller Section...
		//Account Category...
		Route::resource('account-category', AccountCategoryController::class);
		Route::get('account-category-active/{id}', 		[App\Http\Controllers\Backend\Account\AccountCategoryController::class, 'active'])->name('account-category-active');
		Route::get('account-category-inactive/{id}', 	[App\Http\Controllers\Backend\Account\AccountCategoryController::class, 'inactive'])->name('account-category-inactive');
		Route::get('account-category-delete/{id}', 		[App\Http\Controllers\Backend\Account\AccountCategoryController::class, 'destroy'])->name('account-category-delete');
		//Account...
		Route::resource('account', AccountController::class);
		Route::get('account-delete/{id}', 				[App\Http\Controllers\Backend\Account\AccountController::class, 'destroy'])->name('account-delete');
		Route::get('account-active/{id}', 				[App\Http\Controllers\Backend\Account\AccountController::class, 'active'])->name('account-active');
		Route::get('account-inactive/{id}', 			[App\Http\Controllers\Backend\Account\AccountController::class, 'inactive'])->name('account-inactive');
		Route::get('account-profile/{id}', 				[App\Http\Controllers\Backend\Account\AccountController::class, 'accountProfile'])->name('account-profile');
		Route::post('add-transfer-amount', 				[App\Http\Controllers\Backend\Account\AccountController::class, 'addTranserData'])->name('add-transfer-amount');
		Route::post('update-transfer-amount', 			[App\Http\Controllers\Backend\Account\AccountController::class, 'updateTranserData'])->name('update-transfer-amount');
		
		//For Package Controller Section...
		//Package Category
		Route::resource('package-category', PackageCategoryController::class);
		Route::get('package-category-active/{id}', 		[App\Http\Controllers\Backend\PackageManage\PackageCategoryController::class, 'active'])->name('package-category-active');
		Route::get('package-category-inactive/{id}', 	[App\Http\Controllers\Backend\PackageManage\PackageCategoryController::class, 'inactive'])->name('package-category-inactive');
		Route::get('package-category-delete/{id}', 		[App\Http\Controllers\Backend\PackageManage\PackageCategoryController::class, 'destroy'])->name('package-category-delete');
		//Package
		Route::resource('package', PackageController::class);
		Route::get('package-delete/{id}', 				[App\Http\Controllers\Backend\PackageManage\PackageController::class, 'destroy'])->name('package-delete');
		Route::get('package-active/{id}', 				[App\Http\Controllers\Backend\PackageManage\PackageController::class, 'active'])->name('package-active');
		Route::get('package-inactive/{id}', 			[App\Http\Controllers\Backend\PackageManage\PackageController::class, 'inactive'])->name('package-inactive');
		//Account Profile
		Route::get('purchase-account-list', 					[App\Http\Controllers\Backend\PackageManage\PackageController::class, 'purchaseAccountList'])->name('purchase-account-list');
		Route::get('purchase-account-profile/{id}', 			[App\Http\Controllers\Backend\PackageManage\PackageController::class, 'purchaseAccountProfile'])->name('purchase-account-profile');

		//SMS Module...
		Route::resource('sms-dashboard', 	SMSDashboardController::class);
		Route::resource('sms-template', 	SMSTemplateController::class);
		Route::get('sms-template-delete/{id}', 	[App\Http\Controllers\Backend\SMSModule\SMSTemplateController::class, 'destroy'])->name('sms-template-delete');
		Route::resource('notice-sms', 		NoticeSMSController::class);

		//Push Notification Module...
		Route::resource('push-notification', PushNotificationController::class);
		Route::get('/push-notification-delete/{id}', 		[App\Http\Controllers\Backend\PushNotification\PushNotificationController::class, 'destroy'])->name('push-notification-delete');

		//Support Module...
		Route::resource('ticket-support', 		SupportTicketController::class);
		Route::get('ticket-support-close/{id}', 	[App\Http\Controllers\Backend\SupportTicket\SupportTicketController::class, 'supportTicketClose'])->name('ticket-support-close');
		Route::get('ticket-support-details/{id}', 	[App\Http\Controllers\Backend\SupportTicket\SupportTicketController::class, 'supportTicketDetail'])->name('ticket-support-details');
		Route::post('ticket-support-reply/{id}', 	[App\Http\Controllers\Backend\SupportTicket\SupportTicketController::class, 'supportTicketRepply'])->name('ticket-support-reply');

		//For Blog Controller Section...
		//Blog Category
		Route::resource('blog-category', BlogCategoryController::class);
		Route::get('blog-category-delete/{id}', 			[App\Http\Controllers\Backend\BlogManage\BlogCategoryController::class, 'destroy'])->name('blog-category-delete');
		Route::get('blog-category-active/{id}', 			[App\Http\Controllers\Backend\BlogManage\BlogCategoryController::class, 'active'])->name('blog-category-active');
		Route::get('blog-category-inactive/{id}', 			[App\Http\Controllers\Backend\BlogManage\BlogCategoryController::class, 'inactive'])->name('blog-category-inactive');
		//Blog
		Route::resource('blog', BlogController::class);
		Route::get('blog-active/{id}', 						[App\Http\Controllers\Backend\BlogManage\BlogController::class, 'active'])->name('blog-active');
		Route::get('blog-inactive/{id}', 					[App\Http\Controllers\Backend\BlogManage\BlogController::class, 'inactive'])->name('blog-inactive');
		Route::get('blog-delete/{id}', 						[App\Http\Controllers\Backend\BlogManage\BlogController::class, 'destroy'])->name('blog-delete');
		Route::post('upload-image-for-blog',				[App\Http\Controllers\Backend\BlogManage\BlogController::class, 'uploadImage'])->name('upload-image-for-blog');

		//For Documentation Controller Section...
		//Documentation Category
		Route::resource('documentation-category', DocumentationCategoryController::class);
		Route::get('documentation-category-delete/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationCategoryController::class, 'destroy'])->name('documentation-category-delete');
		Route::get('documentation-category-active/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationCategoryController::class, 'active'])->name('documentation-category-active');
		Route::get('documentation-category-inactive/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationCategoryController::class, 'inactive'])->name('documentation-category-inactive');
		//Documentation Tags
		Route::resource('documentation-tag', DocumentationTagController::class);
		Route::get('documentation-tag-delete/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationTagController::class, 'destroy'])->name('documentation-tag-delete');
		Route::get('documentation-tag-active/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationTagController::class, 'active'])->name('documentation-tag-active');
		Route::get('documentation-tag-inactive/{id}', 			[App\Http\Controllers\Backend\DocumentationManage\DocumentationTagController::class, 'inactive'])->name('documentation-tag-inactive');
		//Documentation
		Route::resource('documentation', DocumentationController::class);
		Route::get('documentation-delete/{id}', 					[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'destroy'])->name('documentation-delete');
		Route::get('documentation-list-bn', 						[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'banglaList'])->name('documentation-list-bn');
		Route::get('published-documentation-en', 					[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'englishListPB'])->name('published-documentation-en');
		Route::get('unpublished-documentation-en', 					[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'englishListUP'])->name('unpublished-documentation-en');
		Route::get('draft-documentation-en', 						[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'englishListDN'])->name('draft-documentation-en');
		Route::get('/documentation-tags/search', 					[App\Http\Controllers\Backend\DocumentationManage\DocumentationController::class, 'search'])->name('documentation.tags.search');

		//Expense Management...
		//Payee Controller...
		Route::resource('payee', PayeeController::class);
		Route::get('payee-delete/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\PayeeController::class, 'destroy'])->name('payee-delete');
		Route::get('payee-active/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\PayeeController::class, 'active'])->name('payee-active');
		Route::get('payee-inactive/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\PayeeController::class, 'inactive'])->name('payee-inactive');
		//Expense Category
		Route::resource('expense-category', ExpenseCategoryController::class);
		Route::get('expense-category-delete/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseCategoryController::class, 'destroy'])->name('expense-category-delete');
		Route::get('expense-category-active/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseCategoryController::class, 'active'])->name('expense-category-active');
		Route::get('expense-category-inactive/{id}', 		[App\Http\Controllers\Backend\ExpenseManagement\ExpenseCategoryController::class, 'inactive'])->name('expense-category-inactive');
		//Expense
		Route::resource('expense', ExpenseController::class);
		Route::get('expense-delete/{id}', 					[App\Http\Controllers\Backend\ExpenseManagement\ExpenseController::class, 'destroy'])->name('expense-delete');
		Route::get('expense-active/{id}', 					[App\Http\Controllers\Backend\ExpenseManagement\ExpenseController::class, 'active'])->name('expense-active');
		Route::get('expense-inactive/{id}', 				[App\Http\Controllers\Backend\ExpenseManagement\ExpenseController::class, 'inactive'])->name('expense-inactive');
		//Expense Receipt
		Route::resource('expense-receipt', ExpenseReceiptController::class);
		Route::post('get-all-expense-with-category-id', 	[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'getExpenseWithCategory'])->name('get-all-expense-with-category-id');
		Route::post('expense-receipt-update', 				[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'update'])->name('expense-receipt.update');
		Route::get('expense-receipt-delete-all/{id}', 		[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'deleteExpenseService'])->name('expense-receipt-delete-all');
		Route::post('expense-receipt-delete', 				[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'removeExpenseService'])->name('expense-receipt-delete');
		Route::post('save-all-expense-receipt', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'saveAllExpenseReceipt'])->name('save-all-expense-receipt');
		Route::post('add-expense-receipt-payment', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'addBillPayment'])->name('add-expense-receipt-payment');
		Route::post('add-due-expense-receipt-payment', 		[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'addBillPaymentFD'])->name('add-due-expense-receipt-payment');
		Route::get('get-all-expense-receipt/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'getAllExpenseReceipt'])->name('get-all-expense-receipt');
		Route::get('pending-all-expense-receipt/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'pendingBillPaymentInvoice'])->name('pending-all-expense-receipt');
		Route::get('get-expense-receipt-payment-invoice/{id}', [App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'getBillPaymentInvoice'])->name('get-expense-receipt-payment-invoice');
		Route::get('download-expense-receipt-payment-invoice-page/{id}', 		[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'downloadBillPayInvoicePage'])->name('download-expense-receipt-payment-invoice-page');
		Route::get('print-expense-receipt-payment-invoice-page/{id}', 			[App\Http\Controllers\Backend\ExpenseManagement\ExpenseReceiptController::class, 'printBillPayInvoicePage'])->name('print-expense-receipt-payment-invoice-page');
		Route::post('get-single-product-details', 								[App\Http\Controllers\Backend\ExpenseManagement\BillingPaymentController::class, 'singleSD'])->name('get-single-product-details');

		// Income Management...
		Route::resource('receiver', ReceiverController::class);
		Route::get('receiver-delete/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\ReceiverController::class, 'destroy'])->name('receiver-delete');
		Route::get('receiver-active/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\ReceiverController::class, 'active'])->name('receiver-active');
		Route::get('receiver-inactive/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\ReceiverController::class, 'inactive'])->name('receiver-inactive');
		//Income Category
		Route::resource('income-category', IncomeCategoryController::class);
		Route::get('income-category-delete/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\IncomeCategoryController::class, 'destroy'])->name('income-category-delete');
		Route::get('income-category-active/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\IncomeCategoryController::class, 'active'])->name('income-category-active');
		Route::get('income-category-inactive/{id}', 		[App\Http\Controllers\Backend\IncomeManagement\IncomeCategoryController::class, 'inactive'])->name('income-category-inactive');
		//Income
		Route::resource('income', IncomeController::class);
		Route::get('income-delete/{id}', 					[App\Http\Controllers\Backend\IncomeManagement\IncomeController::class, 'destroy'])->name('income-delete');
		Route::get('income-active/{id}', 					[App\Http\Controllers\Backend\IncomeManagement\IncomeController::class, 'active'])->name('income-active');
		Route::get('income-inactive/{id}', 				[App\Http\Controllers\Backend\IncomeManagement\IncomeController::class, 'inactive'])->name('income-inactive');
		//Income Receipt
		Route::resource('income-receipt', IncomeReceiptController::class);
		Route::post('get-all-income-with-category-id', 	[App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController::class, 'getIncomeWithCategory'])->name('get-all-income-with-category-id');
		Route::post('income-receipt-update', 				[App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController::class, 'update'])->name('income-receipt.update');
		Route::post('income-receipt-delete', 				[App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController::class, 'removeIncomeService'])->name('income-receipt-delete');
		Route::post('save-all-income-receipt', 			[App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController::class, 'saveAllIncomeReceipt'])->name('save-all-income-receipt');
		Route::get('get-all-income-receipt/{id}', 			[App\Http\Controllers\Backend\IncomeManagement\IncomeReceiptController::class, 'getAllIncomeReceipt'])->name('get-all-income-receipt');

		//User & Role...
		Route::resource('roles', RoleController::class);
		Route::get('roles-delete/{id}', 		[App\Http\Controllers\Backend\UserRole\RoleController::class, 'destroy'])->name('roles-delete');
		Route::resource('users', UserController::class);
		Route::post('get-all-package-with-category-id', [App\Http\Controllers\Backend\UserRole\UserController::class, 'getPackages'])->name('get-all-package-with-category-id');
		Route::post('users-package-renew', 				[App\Http\Controllers\Backend\UserRole\UserController::class, 'packageRenew'])->name('users-package-renew');
		Route::get('users-active/{id}', 		[App\Http\Controllers\Backend\UserRole\UserController::class, 'active'])->name('users-active');
		Route::get('users-inactive/{id}', 	    [App\Http\Controllers\Backend\UserRole\UserController::class, 'inactive'])->name('users-inactive');
		Route::get('users-delete/{id}', 		[App\Http\Controllers\Backend\UserRole\UserController::class, 'destroy'])->name('users-delete');

		//Setting & Other Route is Here...
		Route::get('/setting', 								[App\Http\Controllers\Backend\Setting\SettingController::class, 'index'])->name('setting');
		Route::post('/logo/save', 							[App\Http\Controllers\Backend\Setting\SettingController::class, 'store'])->name('logo.store');
		Route::post('/logo/update/{id}', 					[App\Http\Controllers\Backend\Setting\SettingController::class, 'update'])->name('logo.update');
		Route::post('/footer-text-submit', 					[App\Http\Controllers\Backend\Setting\SettingController::class, 'footerTextSubmit'])->name('footer-text-submit');
		Route::post('/invoice-logo/save', 					[App\Http\Controllers\Backend\Setting\SettingController::class, 'invoiceLogoSave'])->name('invoice-logo.store');
		Route::post('/invoice-logo/update/{id}', 			[App\Http\Controllers\Backend\Setting\SettingController::class, 'invoiceLogoUpdate'])->name('invoice-logo.update');
		Route::get('/user-activity', 						[App\Http\Controllers\Backend\Setting\SettingController::class, 'getUserActivity'])->name('user-activity');
		//Contact Form Data...
		Route::get('/contact-form-data', 					[App\Http\Controllers\Backend\Setting\SettingController::class, 'getContactFormData'])->name('contact-form-data');
		Route::get('/contact-form-solved/{id}', 			[App\Http\Controllers\Backend\Setting\SettingController::class, 'getContactFormSolved'])->name('contact-form-solved');
		Route::get('/contact-form-unsolved/{id}', 			[App\Http\Controllers\Backend\Setting\SettingController::class, 'getContactFormUnsolved'])->name('contact-form-unsolved');
		//Gallery
		Route::resource('gallery', GalleryController::class);
		Route::get('gallery-active/{id}', 						[App\Http\Controllers\Backend\Setting\GalleryController::class, 'active'])->name('gallery-active');
		Route::get('gallery-inactive/{id}', 					[App\Http\Controllers\Backend\Setting\GalleryController::class, 'inactive'])->name('gallery-inactive');
		Route::get('gallery-delete/{id}', 						[App\Http\Controllers\Backend\Setting\GalleryController::class, 'destroy'])->name('gallery-delete');
		//Zone Controller...
		Route::resource('zone', ZoneController::class);
		Route::get('division-list', 			[App\Http\Controllers\Backend\Setting\ZoneController::class, 'divisionList'])->name('division-list');
		Route::get('upozila-list', 				[App\Http\Controllers\Backend\Setting\ZoneController::class, 'upozilaList'])->name('upozila-list');
		Route::get('district-list', 			[App\Http\Controllers\Backend\Setting\ZoneController::class, 'districtList'])->name('district-list');
		Route::get('zone-delete/{id}', 			[App\Http\Controllers\Backend\Setting\ZoneController::class, 'destroy'])->name('zone-delete');

		//To Invoice Print...
		//Expense
		Route::get('print-all-expense-receipt/{id}',		[App\Http\Controllers\Backend\PrintInvoice\PrintInvoiceController::class, 'printAExRe'])->name('print-all-expense-receipt');
	});

});

require __DIR__.'/webuser.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
