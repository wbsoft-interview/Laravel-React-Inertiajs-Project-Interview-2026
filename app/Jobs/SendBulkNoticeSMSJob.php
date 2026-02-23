<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\NoticeSMS;
use App\Models\SMSTemplate;
use App\Models\AdminPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;

class SendBulkNoticeSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userData;
    public $title;
    public $details;
    public $userId;
    public $userIdFCU;
    public $userSupId;
    public $templateId;

    public function __construct($userData, $title, $details, $userId, $userIdFCU, $userSupId, $templateId)
    {
        $this->userData = $userData;
        $this->title = $title;
        $this->details = $details;
        $this->userId = $userId;
        $this->userIdFCU = $userIdFCU;
        $this->userSupId = $userSupId;
        $this->templateId = $templateId;
    }

    public function handle()
    {
        foreach ($this->userData as $receiver) {

            if (empty($receiver->mobile)) {
                continue;
            }

            DB::transaction(function () use ($receiver) {

                $adminSMSBalance = AdminPackage::where('user_id', $this->userSupId)
                    ->where('package_by', $this->userId)
                    ->lockForUpdate()
                    ->first();

                if (!$adminSMSBalance || $adminSMSBalance->sms_remaining <= 0) {
                    return;
                }

                $personalizedDetails = str_replace(
                    ['{name}', '{email}', '{mobile_no}'],
                    [$receiver->name ?? '', $receiver->email ?? '', $receiver->mobile ?? ''],
                    $this->details
                );

                //To Send SMS...
                $this->sendSMS($this->title, $personalizedDetails, $receiver->mobile);

                //To deduct exact sms...
                $adminSMSBalance->decrement('sms_remaining');

                //To Save notice data...
                NoticeSMS::create([
                    'user_id' => $this->userId,
                    'sms_from_id' => $this->userIdFCU,
                    'sms_to_id' => $receiver->id,
                    'sms_template_id' => $this->templateId,
                    'title' => $this->title,
                    'details' => $personalizedDetails,
                ]);
            });
        }
    }


    private function sendSMS($title, $details, $mobile)
    {
        $message = "{$details}";
        $data = [
            "UserName" => "wbsoft.net@gmail.com",
            "Apikey" => "PMHJ4UCXBP79E2E8AMYVUF3KR",
            "MobileNumber" => '88'.$mobile,
            "CampaignId" => "null",
            "SenderName" => "8809601004746",
            "TransactionType" => "T",
            "Message" => $message,
        ];

        $ch = curl_init("https://api.mimsms.com/api/SmsSending/SMS");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
