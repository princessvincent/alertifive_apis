<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $stopic, $message, $title, $group_id;

    public function __construct($message, $title, $stopic, $group_id)
    {
        $this->message = $message;
        $this->title = $title;
        $this->stopic = $stopic;
        $this->group_id = $group_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {

        echo "sending call push notification to $this->stopic";

        $topic='11';

        $messaging = app('firebase.messaging');
        $message = CloudMessage::withTarget('topic', "11")
            ->withNotification(Notification::create($this->title, $this->message))
            ->withData(['group_id' => $this->group_id]);

        $messaging->send($message);

        echo $messaging;

//        $payload = '{
//    "to": "/topics/' . $this->stopic . '",
//    "data": {
//        "priority":"high"
//        "extra_information": "group",
//        "id":"' . $this->group_id . '"
//    },
//    "notification": {
//        "title": "' . $this->title . '",
//        "body": "' . $this->message . '"
//    },
//    "priority":"high"
//}';
//
//        Log::info("Push notification for Group");
//        Log::info($payload);
//
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_SSL_VERIFYPEER => false,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => $payload,
//            CURLOPT_HTTPHEADER => array(
//                "Authorization: key=" . env('PUSH_NOTIFICATION_KEY'),
//                "Content-Type: application/json",
//                "Content-Type: text/plain"
//            ),
//        ));
//        $uresponse = curl_exec($curl);
//
//        echo $uresponse;

    }
}
