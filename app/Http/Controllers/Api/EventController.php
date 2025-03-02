<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;  //x nidificare tutto il return dentro un  "data": ...
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  # use Postman software with GET url http://127.0.0.1:8000/api/events
        return EventResource::collection(Event::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   # use Postman software with POST url http://127.0.0.1:8000/api/events + set headers key=Accept value=application/json  (xk se no di return hai html(e return l'ultimo trovato quindi la home page html))
        #body raw  {"name":"First event","start_time":"2023-07-01 15:00:00","end_time":"2023-07-01 16:00:00"}
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]),
            'user_id' => 1
        ]);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {  #Postman GET url http://127.0.0.1:8000/api/events/2
        $event->load('user','attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {       # use Postman software with PUT url http://127.0.0.1:8000/api/events/2  + body raw {"name": "My edited Text!"}
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after:start_time',
            ])
        );
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {   #Postman DELETE url http://127.0.0.1:8000/api/events/2
        $event->delete();
        // return response()->json([
        //     'message' => 'Event deleted successfully'  #x debug
        // ]);
        return response(status:204);  //mex return 'No Content Available' means target doesn't exist anymore
    }
}
