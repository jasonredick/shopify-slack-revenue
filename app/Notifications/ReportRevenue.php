<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ReportRevenue extends Notification
{
    use Queueable;

    protected $message = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($string)
    {
        $this->message = $string;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)->content($this->message);
    }
}
