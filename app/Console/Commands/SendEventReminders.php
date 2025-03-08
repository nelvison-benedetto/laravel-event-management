<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';  //php artisan command to send notifications

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that event starts soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time',[now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        $this->info("Found {$eventCount} {$eventLabel}");
        $events->each(
            fn ($event)=> $event->attendees->each(
                    //fn($attendee)=>$this->info("Notifying the user {$attendee->user->id}")
                    fn($attendee) => $attendee->user->notify(
                        new EventReminderNotification(
                            $event
                        )
                    )
                )
        ); //now with php artisan app:send-event-reminders you will see  Found X events
        $this->info('Reminder notifications sent successfully');
          //run on console with php artisan app:send-event-reminders
          //quindi se vuoi inviare il reminder a tutti ogni giorno(esempio)
             //$schedule->command('app:send-event-reminders')->daily();  //questo aggiungilo in Schedule(){} in Kernel.php

    }
}
