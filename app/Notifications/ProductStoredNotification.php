<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductStoredNotification extends Notification
{
    use Queueable;

    private $message;
    private $channel;
    private $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, string $channel = 'mail', array $data = null)
    {
        $this->message = $message;
        $this->channel = $channel;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [$this->channel];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line($this->message)
                    ->lineIf(! is_null($this->data) > 0, "product id: {$this->data['id']}")
                    ->lineIf(! is_null($this->data) > 0, "created by: {$this->data['creator']['first_name']} {$this->data['creator']['last_name']} ");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
