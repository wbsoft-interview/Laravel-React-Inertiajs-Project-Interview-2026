<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\AdminPackage;
use App\Models\AdminPackageHistory;
use App\Models\Account;
use App\Models\AccountTransfer;
use App\Models\TciketBillPayBkash;
use App\Models\PackageCategory;
use App\Models\Package;
use App\Models\FooterText;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Inertia\Inertia;
use Auth;
use DB;

class AdminController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;
    private $finalToken;

    public function __construct()
    {
        $this->base_url   = config('bkash.base_url');
        $this->app_key    = config('bkash.app_key');
        $this->app_secret = config('bkash.app_secret');
        $this->username   = config('bkash.username');
        $this->password   = config('bkash.password');
        $this->finalToken = '';
    }

    public function adminDashboard()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$logo = DB::table('logos')->where('user_id',$userId)->first();
        $footerText = FooterText::where('user_id', $userId)->first();
        $userCount = User::orderBy('id','desc')->count();

        return Inertia::render('Backend/Dashboard', [
            'logo' => $logo,
            'userCount' => $userCount,
            'canUserList' => Auth::user()->can('user-list'),
            'footerText' => $footerText ? $footerText->solid_text : '',
        ]);
    }
    
    public function najmun()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$logo = DB::table('logos')->where('user_id',$userId)->first();
        $footerText = FooterText::where('user_id', $userId)->first();
        $userCount = User::orderBy('id','desc')->count();

        return Inertia::render('Backend/Others/Najmun', [
            'logo' => $logo,
            'userCount' => $userCount,
            'canUserList' => Auth::user()->can('user-list'),
            'footerText' => $footerText ? $footerText->solid_text : '',
        ]);
    }
    
    public function adminDashboard2()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$logo = DB::table('logos')->where('user_id',$userId)->first();
        $userCount = User::orderBy('id','desc')->count();

        return view('backend.dashboard',compact('logo','userCount'));
    }
    
    public function renewPage()
    {   
        //To get current user...
        $userId = CurrentUser::getOwnerId();
    	$activePackage = AdminPackage::where('package_by', $userId)->first();

        return view('backend.renewPackage.index',compact('activePackage'))->with('success', 'Dashboard loaded successfully!');
    }

    //To update package...
    public function saveRenewData(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'package_category_id' => 'required|exists:package_categories,id',
            'package_id' => 'required|exists:packages,id',
        ]);
        
        //To fet userId..
        $userSupId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userSupId;

        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->admin_id);
            $package = Package::findOrFail($request->package_id);
            $activePackage = AdminPackage::where('package_by', $user->id)->first();
            
            $now = Carbon::now();

            if ($activePackage) {
                $newEndDate = $activePackage->end_date && Carbon::parse($activePackage->end_date)->gt($now)
                    ? Carbon::parse($activePackage->end_date)->addDays($package->package_validity)
                    : $now->copy()->addDays($package->package_validity);
        
                $activePackage->update([
                    'package_by'         => $user->id,
                    'package_id'         => $package->id,
                    'end_date'           => $newEndDate,
                    'sms_remaining'      => $activePackage->sms_remaining + $package->sms_qty,
                    'student_remaining'  => $package->student_qty,
                    'status'             => 'active',
                ]);
                
                $adminPHDData = AdminPackageHistory::create([
                    'user_id'       => $userSupId,
                    'package_by'    => $user->id,
                    'assigned_by'   => $userSupId,
                    'package_id'    => $package->id,
                    'start_date'    => $now,
                    'end_date'      => $now->copy()->addDays($package->package_validity),
                    'sms_qty'       => $package->sms_qty,
                    'student_qty'   => $package->student_qty,
                    'status'        => $activePackage->status,
                ]);

                //To get bKash token...
                $tokenResponse = $this->getToken();
                $token = json_decode($tokenResponse->getContent(), true);
                $id_token = $token['id_token'] ?? null;
                if (!$id_token) {
                    return back()->with('error', 'Failed to get bKash token');
                }
         
                $fixedInvoice = $adminPHDData->id;
                // $fixedInvoice = 98;
                $client = new \GuzzleHttp\Client();
                $response = $client->post($this->base_url . '/create', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => $id_token,
                        'X-APP-Key' => $this->app_key,
                    ],
                    'json' => [
                        'mode' => "0011",
                        'payerReference' => $user->login_mobile,
                        'callbackURL' => route('bkash.callback'),
                        'amount' => (string)$package->package_price,
                        'currency' => 'BDT',
                        'intent' => 'sale',
                        'merchantInvoiceNumber' => $fixedInvoice,
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                //To check duplicate...
                $existingPayment = TciketBillPayBkash::where('merchant_invoice_number', $fixedInvoice)
                                     ->first();
                if ($existingPayment) {
                    DB::rollBack();
                    return redirect()->route('package-renew')->with('error', 'Duplicate payment prevented! This invoice is already in process or completed with the merchant invoice numbe = #PKG.'.$existingPayment->merchant_invoice_number);
                }


                TciketBillPayBkash::create(
                    [
                        'ticketing_id' => $fixedInvoice,
                        'token_id' => $id_token,
                        'payment_id' => $data['paymentID'],
                        'currency' => $data['currency'],
                        'intent' => $data['intent'],
                        'merchant_invoice_number' => $fixedInvoice,
                        'total_amount' => $package->package_price,
                    ]

                );
                
                DB::commit();

                return redirect($data['bkashURL']);

            } else {
                $newPackage = AdminPackage::create([
                    'user_id'            => $userSupId,
                    'package_by'         => $user->id,
                    'package_id'         => $package->id,
                    'start_date'         => $now,
                    'end_date'           => $now->copy()->addDays($package->package_validity),
                    'sms_remaining'      => $package->sms_qty,
                    'student_remaining'  => $package->student_qty,
                    'status'             => 'active',
                ]);

                $adminPHDData = AdminPackageHistory::create([
                    'user_id'       => $userSupId,
                    'package_by'    => $newPackage->package_by,
                    'package_id'    => $newPackage->package_id,
                    'start_date'    => $newPackage->start_date,
                    'end_date'      => $newPackage->end_date,
                    'sms_qty'       => $newPackage->sms_remaining,
                    'student_qty'   => $newPackage->student_remaining,
                    'assigned_by'   => $userSupId,
                ]);
                
                //To get bKash token...
                $tokenResponse = $this->getToken();
                $token = json_decode($tokenResponse->getContent(), true);
                $id_token = $token['id_token'] ?? null;
                if (!$id_token) {
                    return back()->with('error', 'Failed to get bKash token');
                }

                $fixedInvoice = $adminPHDData->id;
                $client = new \GuzzleHttp\Client();
                $response = $client->post($this->base_url . '/create', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => $id_token,
                        'X-APP-Key' => $this->app_key,
                    ],
                    'json' => [
                        'mode' => "0011",
                        'payerReference' => $user->login_mobile,
                        'callbackURL' => route('bkash.callback'),
                        'amount' => (string)$package->package_price,
                        'currency' => 'BDT',
                        'intent' => 'sale',
                        'merchantInvoiceNumber' => $fixedInvoice,
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                //To check duplicate...
                $existingPayment = TciketBillPayBkash::where('merchant_invoice_number', $fixedInvoice)
                                     ->first();
                if ($existingPayment) {
                    DB::rollBack();
                    return redirect()->route('package-renew')->with('error', 'Duplicate payment prevented! This invoice is already in process or completed.');
                }

                TciketBillPayBkash::updateOrCreate(
                    ['ticketing_id' => $fixedInvoice],
                    [
                        'token_id' => $id_token,
                        'payment_id' => $data['paymentID'],
                        'currency' => $data['currency'],
                        'intent' => $data['intent'],
                        'merchant_invoice_number' => $fixedInvoice,
                        'total_amount' => $package->package_price,
                    ]
                );
                
                DB::commit();
                return redirect($data['bkashURL']);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong: '.$e->getMessage(),
                    'status_code' => 500
                ]);
            }

            Toastr::error('Something went wrong!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    // Function to generate random uppercase letter
    private function getRandomUppercaseLetter() {
        return chr(mt_rand(65, 90)); // ASCII values for uppercase letters
    }

    // Function to generate random digit
    private function getRandomDigit() {
        return mt_rand(0, 9);
    }

    // Generate random ticketing ID
    private function generateTicketingID() {
        $ticketingID = '';

        // Generate 4 uppercase letters
        for ($i = 0; $i < 4; $i++) {
            $ticketingID .= $this->getRandomUppercaseLetter();
        }

        // Generate 4 digits
        for ($i = 0; $i < 4; $i++) {
            $ticketingID .= $this->getRandomDigit();
        }

        return $ticketingID.'BD';
    }

    public function getToken()
    {
        $client = new Client();
        
        $response = $client->post($this->base_url . '/token/grant', [
            'headers' => [
                'username' => $this->username,
                'password' => $this->password,
            ],
            'json' => [
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        
        return response()->json($data);
    }

    //To get invoice page...
    public function bkashCallback(Request $request)
    {
        //To fet userId..
        $now = Carbon::now();
        $userSupId = CurrentUser::getSuperadminId();
        $paymentID = $request->paymentID;
        if (!$paymentID) {
            return redirect()->route('package-renew')->with('error', 'Sorry !! Invalid payment ID!.');
        }
        
        $bkashPay = TciketBillPayBkash::where('payment_id', $paymentID)->first();
        if(!$bkashPay){
            return redirect()->route('package-renew')->with('error', 'Sorry !! Payment not found!.');
        }

        $ticketing = AdminPackageHistory::find($bkashPay->ticketing_id);
        if (!$ticketing) {
            return redirect()->route('package-renew')->with('error', 'Sorry !! Ticketing info missing!.');
        }
        $package = Package::findOrFail($ticketing->package_id);

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($this->base_url . '/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $bkashPay->token_id,
                    'X-APP-Key' => $this->app_key,
                ],
                'json' => [
                    'paymentID' => $paymentID
                ]
            ]);

            $data = json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            $this->rollbackPackage($ticketing);

            return redirect()->route('package-renew')->with('error', 'Sorry !! BKash execute request failed!.');
        }

        if (!isset($data['transactionStatus'])) {
            $this->rollbackPackage($ticketing);
            return redirect()->route('package-renew')->with('error', 'Sorry !! Payment response invalid!');
        }

        switch ($data['transactionStatus']) {
            case 'Completed':

                // if($bkashPay->status === 'Completed'){
                //     $this->rollbackPackageUP($ticketing);
                //     return redirect()->route('view-package-purchase-invoice', $ticketing->id)->with('error', 'Sorry !! Duplicate payment detected! This transaction has already been completed!');
                // }
                
                //To update...
                $bkashPay->update([
                    'trx_id' => $data['trxID'] ?? null,
                    'status' => 'Completed',
                    'is_bkash_payment' => 1,
                    'is_bkash_execute' => 1
                ]);

                //To update...
                $ticketing->update([
                    'ticketing_status' => 1,
                    'is_ticketing_pay' => 1
                ]);

                //To update account balance...
                $account = Account::firstOrCreate(
                    ['id' => $userSupId],
                    [
                        'account_name' => 'Main Account',
                        'account_holder_name' => 'Admin',
                        'account_number' => '01774444000',
                        'account_balance' => 0,
                        'status' => 1,
                        'user_id' => 1,
                    ]
                );

                $account->increment('account_balance', $package->package_price);
                AccountTransfer::create([
                    'account_id'          => $account->id,
                    'transfer_by'         => $userSupId,
                    'transfer_type'       => 'Credit',
                    'transfer_amount'     => $package->package_price,
                    'current_amount'      => $account->account_balance,
                    'transfer_date'       => $now,
                    'transfer_purpuse'    => 'Package purchase: ' . $package->package_name,
                    'status'              => true,
                ]);

                return redirect()->route('view-package-purchase-invoice', $ticketing->id)->with('success', 'Success !! Payment successful!');

            case 'Failed':
                $this->rollbackPackageUP($ticketing);
                return redirect()->route('package-renew')->with('error', 'Sorry !! Payment Failed!.');

            case 'Cancelled':
                $this->rollbackPackageUP($ticketing);
                return redirect()->route('package-renew')->with('error', 'Sorry !! Payment Cancelled!');

            default:
                $this->rollbackPackage($ticketing);
                
                $errorMessage = $data['errorMessage'] ?? 'Payment status unknown!';
                return redirect()->route('package-renew')->with('error', 'Sorry !!'.$errorMessage);
        }
    }

    private function rollbackPackage(AdminPackageHistory $ticketing)
    {
        $now = Carbon::now();
        $package = Package::findOrFail($ticketing->package_id);
        $activePackage = AdminPackage::where('package_by', $ticketing->package_by)->first();

        if ($activePackage && $activePackage->end_date) {
            $activePackage->end_date = Carbon::parse($activePackage->end_date)->gt($now)
                ? Carbon::parse($activePackage->end_date)->subDays($package->package_validity)
                : $now->copy()->subDays($package->package_validity);

            $activePackage->sms_remaining -= $package->sms_qty;
            $activePackage->save();
        }

        $ticketing->delete();
    }
    
    private function rollbackPackageUP(AdminPackageHistory $ticketing)
    {
        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $now = Carbon::now();
        $package = Package::findOrFail($ticketing->package_id);
        $activePackage = AdminPackage::where('package_by', $ticketing->package_by)->first();

        if ($activePackage && $activePackage->end_date) {
            $activePackage->end_date = Carbon::parse($activePackage->end_date)->gt($now)
                ? Carbon::parse($activePackage->end_date)->subDays($package->package_validity)
                : $now->copy()->subDays($package->package_validity);

            $activePackage->sms_remaining -= $package->sms_qty;
            $activePackage->save();
        }

        AdminPackageHistory::whereIn('package_by', $userId)->where('ticketing_status', 0)->where('is_ticketing_pay', 0)->delete();
    }


    //To shown invoice...
    public function showInvoice($ticketingId)
    {
        $ticketing = AdminPackageHistory::with([
            'packageData',
            'assignedData',
            'packageByData',
            'userData' 
        ])->findOrFail($ticketingId);
        $activePackage = AdminPackage::where('package_by', $ticketing->package_by)->first();
        
        return view('backend.renewPackage.getPurchaseInvoice', [
            'ticketing' => $ticketing,
            'activePackage' => $activePackage,
        ]);
    }

    public function adminLogout()
    {
     Auth::guard('web')->logout();
     return Redirect()->route('admin.login');
    }
}
