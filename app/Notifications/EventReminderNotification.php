<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;  //permette le queues
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue  //ShouldQueue x sends notifications in background, senza bloccare l'applicazione!
{
    use Queueable;  //(anche senza queue,questo esiste) usalo x le queues!
        //crea tab x le code nel db
          //php artisan queue:table
          //php artisan migrate
        //run with   php artisan queue:work   x sends le email in background!
    /**
     * Create a new notification instance.
     */
    public function __construct(public Event $event)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Reminder: You have an upcoming event!')
                    ->action('View Event', route('events.show', $this->event->id))
                    ->line(
                        'The event {$this->event->name} starts at {$this->event->start_time}'
                    );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_start_time' => $this->event->start_time
        ];
    }
}
