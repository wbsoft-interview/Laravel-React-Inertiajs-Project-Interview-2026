<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;




Artisan::command('sms:run-all', function () {
    $this->call('sms:send-scheduled-notification');
});


// Artisan::command('sms:send-scheduled-notification', function () {
//     $this->call(\App\Console\Commands\SendScheduledNotificationSMS::class);
// })->describe('Send scheduled notification SMS');
