<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AttendeeController extends Controller
{
    use CanLoadRelationships;

    //private array $relations = ['user']; è sconsigliato usarlo xk crea relations nesting?INFO

    public function __construct(){  //puoi anche usare this-> se ha la policy x attendee
        Gate::middleware('auth:sanctum')->except(['index', 'show','update']);
        //$this->authorizeResource(Attendee::class, 'attendee');  //authorizeResource NON FUNZIONA X GLI ANNIDATI, qua Attendee dipende da Event, lrv si aspetta param attendee ma invece riceve event/{event}/attendees/{attendee}
    }

    public function index(Event $event)  // //http://127.0.0.1:8000/api/events/3/attendees
    {
        // $attendees = $event->attendees()->latest();
        // return AttendeeResource::collection(
        //     $attendees->paginate()
        // );
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );
        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    public function store(Request $request, Event $event)
    {
        // $attendee = $event->attendees()->create([
        //     'user_id' => 1
        // ]);
        // return new AttendeeResource($attendee);
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );
        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)  //http://127.0.0.1:8000/api/events/3/attendees/1939 + headers params key:Accept value:application/js
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        //THIS-> no usa invece Gate::, per usarlo devi avere una policy!(x tenere separata logica di auth da controller) php artisan make:policy AttendeePolicy --model=Attendee
        //Gate::authorize('delete-attendee', [$event, $attendee]);
        Gate::authorize('delete', $attendee);
        $attendee->delete();
        return response()->noContent();   //in postaman you will see 204 No Content
    }
}
