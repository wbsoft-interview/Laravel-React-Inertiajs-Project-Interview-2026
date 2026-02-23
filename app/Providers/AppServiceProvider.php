<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Observers\ActivityObserver;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use App\Models\{
    User,
    Blog,
    Package,
    Expense,
    Income,
    Zone,
    PhotoGallery,
    SupportTicket
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        
        $models = [
            Role::class,
            User::class,
            Blog::class,
            Package::class,
            Expense::class,
            Income::class,
            Zone::class,
            PhotoGallery::class,
            SupportTicket::class,
        ];

        foreach ($models as $model) {
            $model::observe(ActivityObserver::class);
        }

        Inertia::share([
            'auth' => function () {
                if (auth()->check()) {
                    $user = auth()->user();
                    $can = Permission::pluck('name')
                            ->mapWithKeys(fn ($permission) => [
                                $permission => $user->can($permission),
                            ])
                            ->toArray();
                                
                    return [
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'mobile' => $user->mobile,
                            'role' => $user->role,
                            'image' => $user->image,
                        ],
                        'can' => $can,
                    ];
                }
                return [
                    'user' => null,
                    'can' => [],
                ];
            },
        ]);
    }
}
