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

        $topic=$this->stopic;

        $messaging = app('firebase.messaging');
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($this->title, $this->message))
            ->withData(['group_id' => $this->group_id]);

        $messaging->send($message);

//        echo $messaging;

    }
}
