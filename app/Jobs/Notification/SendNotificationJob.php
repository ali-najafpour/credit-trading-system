<?php

namespace App\Jobs\Notification;

use App\Models\User;
use App\Notifications\ProductStored;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductStoredNotification;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $to_user_id;
    private $to_role;
    private $channel;
    private $data;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;
    public $tries = 1;
    public $timeout = 60; //seconds
    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $message, int $to_user_id = null, string $to_role = null, string $channel = null, array $data = null)
    {
        $this->message = $message;
        $this->to_user_id = $to_user_id;
        $this->to_role = $to_role;
        $this->channel = $channel;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = $this->message;
        $to_user_id = $this->to_user_id;
        $to_role = $this->to_role;
        $channel = $this->channel;
        $data = $this->data;

        $to_users = [];
        if(! is_null($to_user_id)){
            array_push($to_users, $to_user_id);
        }
        if(! is_null($to_role)){
            $user_ids = User::query()->isManager()->isActive()->pluck('id')->toArray();
            foreach($user_ids as $user_id){
                array_push($to_users, $user_id);
            }
        }

        $to_users = User::query()->whereIn('id',$to_users)->isActive()->get();

        Notification::send($to_users, new ProductStoredNotification($message,$channel,$data));

    }
}
