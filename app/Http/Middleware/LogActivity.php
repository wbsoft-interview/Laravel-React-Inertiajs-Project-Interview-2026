<?php

namespace App\Http\Middleware;

use App\Helpers\CurrentUser;
use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = CurrentUser::getOwnerId();
            $userIdFCU = CurrentUser::getUserIdFCU();
            $ip = $request->ip();

            $module = $request->route()?->getName() ?? $request->path();

            $excludedRoutes = [
                'user-activity',
                'login',
                'logout',
                'password.request',
                'password.email',
                'password.reset',
            ];

            if (!in_array($module, $excludedRoutes)) {
                if (!in_array(strtolower(Auth::user()->role), ['superadmin'])) {
                    $parts = explode('.', $module);
                    $moduleName = Str::title(str_replace('-', ' ', $parts[0] ?? 'Unknown'));
                    if (isset($parts[1])) {
                        $actionPart = Str::title(str_replace('-', ' ', $parts[1]));
                    } else {
                        $subParts = explode('-', $parts[0]);
                        $actionPart = Str::title(end($subParts));
                    }

                    $description = "Accessed route: \"{$actionPart}\"";
                    // $description = "User accessed {$moduleName}: \"{$actionPart}\"";



                    ActivityLogger::action(
                        'access',
                        $description,
                        $moduleName
                    );
                }
            }
        }

        return $next($request);
    }
}
