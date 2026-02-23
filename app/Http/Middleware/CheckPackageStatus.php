<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AdminPackage;
use App\Helpers\CurrentUser;

class CheckPackageStatus
{
    public function handle(Request $request, Closure $next)
    {
        //To fet userId..
        $userOwnerId = CurrentUser::getOwnerId();
        $user = Auth::user();

        //To check superadmin role...
        if (!$user || $user->role === 'superadmin') {
            return $next($request);
        }

        $activePackage = AdminPackage::where('package_by', $userOwnerId)
            ->where('status', 'active')
            ->first();

        if (!$activePackage || Carbon::parse($activePackage->end_date)->lt(now())){
            //To set session...
            session(['package_expired' => true]);

            if (!$request->routeIs('package-renew') && !$request->routeIs('admin.logout') && !$request->routeIs('save-package-renew')){
                return redirect()->route('package-renew');
            }

        } else {
            session(['package_expired' => false]);
        }

        return $next($request);
    }
}
