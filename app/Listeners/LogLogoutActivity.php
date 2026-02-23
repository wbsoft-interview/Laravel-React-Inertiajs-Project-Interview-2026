<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Logout;

class LogLogoutActivity
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        ActivityLogger::action('logout', 'Logged out.');
    }
}
