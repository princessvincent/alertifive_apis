<?php

namespace App\Jobs;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $message;

    public function __construct(Message $message)
    {
        $this->message=$message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groups=Group::where("user_id", $this->message->user_id)->get();

        foreach ($groups as $group){
            if($group->auto_notify == 1) {
                $keywords = $groups->keywords;

                foreach ($keywords as $keyword) {
                    if ($keyword->sender_name == $this->message->sender){
                        PushNotificationJob::dispatch($this->message->message, "New Message from $group->name", $group->id, $group->id);
                    }
                }
            }
        }

    }
}
