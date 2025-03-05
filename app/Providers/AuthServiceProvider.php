<?php

namespace App\Providers;

//use Illuminate\Support\ServiceProvider;

use App\Models\Attendee;
use App\Models\Event;
use App\Policies\AttendeePolicy;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;



class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Event::class => EventPolicy::class,
        Attendee::class => AttendeePolicy::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //now instead i'm using the policies!
        // Gate::define('update-event', function($user, Event $event){
        //     return $user->id == $event->user_id;
        // });

        // Gate::define('delete-attendee', function($user, Event $event, Attendee $attendee){
        //     return $user->id === $event->user_id  || $user->id === $attendee->user_id;
        // });
    }
}
