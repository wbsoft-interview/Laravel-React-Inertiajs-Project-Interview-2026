<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;

class LogLoginActivity
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        ActivityLogger::action('login', 'Logged in.');
    }
}
