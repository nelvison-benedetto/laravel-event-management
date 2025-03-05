<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    //private array $relations = ['user']; è sconsigliato usarlo xk crea relations nesting?INFO

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
    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();
        return response()->noContent();   //in postaman you will see 204 No Content
    }
}
