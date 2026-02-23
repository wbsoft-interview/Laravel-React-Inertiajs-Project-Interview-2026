<?php

namespace App\Console\Commands;

use Spatie\Permission\Models\Role;
use App\Models\PushNotification;
use App\Models\User;
use App\Models\SMSTemplate;
use App\Models\AdminPackage;
use App\Jobs\SendBulkScheduleSMSJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Helpers\CurrentUser;

class SendScheduledNotificationSMS extends Command
{
    protected $signature = 'sms:send-scheduled-notification';
    protected $description = 'Send scheduled notification SMS';

    public function handle()
    {
        \Log::info("SendBulkScheduleSMSJob started for PushNotification ID:");

        // Use Bangladesh timezone
        $now = Carbon::now('Asia/Dhaka');
        $userSupId = 1;


        // Get all notifications scheduled for today and not yet sent
        $notifications = PushNotification::where('is_scheduler', true)
                        ->where('is_sent', false)
                        ->whereDate('sending_date', $now->format('Y-m-d'))
                        ->get();


        foreach ($notifications as $notification) {

            $role = Role::find($notification->role_id);
            if (!$role) continue;

            $users = User::where('role', $role->name)
                ->where('admin_id', $notification->user_id)
                ->where('status', true)
                ->get();

            if ($users->isEmpty()) continue;

            $template = SMSTemplate::find($notification->sms_template_id);
            if (!$template) continue;

            $adminSMSBalance = AdminPackage::where('user_id', $userSupId)
                                ->where('package_by', $notification->user_id)
                                ->first();

            if (!$adminSMSBalance || $adminSMSBalance->sms_remaining < $users->count()) {
                $this->info("Skipping notification ID {$notification->id}. Not enough SMS balance.");
                continue;
            }

            // Dispatch job
            dispatch(new SendBulkScheduleSMSJob(
                $users,
                $template->sms_title,
                $template->sms_details,
                $userId = $notification->user_id,
                $userIdFCU = $notification->user_id,
                $userSupId,
                $template->id,
                $notification->id
            ));

            $this->info("Dispatched SendBulkScheduleSMSJob for notification ID: {$notification->id}");

            $notification->update([
                'status' => true,
                'is_sent' => true,
                'sent_at' => Carbon::now(),
            ]);
        }
    }
}
