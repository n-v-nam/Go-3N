<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuggestTruckForDriver extends Notification
{
    use Queueable;
    protected $link;
    protected $title;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($link, $title)
    {
        $this->link = $link;
        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->link;
        return (new MailMessage)
                    ->line($this->title)
                    ->action('Xem chi tiết', url($url))
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ của nhóm chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
