<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;  //x nidificare tutto il return dentro un  "data": ...
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;
    use AuthorizesRequests;
    private array $relations = ['user','attendees','attendees.user'];  //BETTER if also readonly!

    public function __construct(){
        //$this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Event::class, 'event');  //apply policy EventPolicy to Event model (lrv check if user can do the deed), each time a route is called
    }

    public function index()
    {  # use Postman software with GET url http://127.0.0.1:8000/api/events +  ?include= user/attendees/attendees.user o le combinazioni di questi 3
           //i.e. http://127.0.0.1:8000/api/events?include=user,attendees,attendee.user

        $query = $this->loadRelationships(Event::query());
        return EventResource::collection(  //EventResource::collection correct json format x response
            $query->latest()->paginate()  //latest() order desc x data, paginate() subdivide res in multiple pages
        );
    }

    // protected function shouldIncludeRelation(string $relation):bool{  //is in CanLoadRelationships.php
    //     $include = request()->query('include');
    //     if(!$include){
    //         return false;
    //     }
    //     $relations =array_map('trim',explode(',',$include)) ;
    //     //dd($relations);  //x debug, preview GET http://127.0.0.1:8000/api/events?include=user,attendees,attendees.user
    //     return in_array($relation,$relations);
    // }



    public function store(Request $request)
    {   # use Postman software with POST url http://127.0.0.1:8000/api/events + set headers key=Accept value=application/json  (xk se no di return hai html(e return l'ultimo trovato quindi la home page html))
        #body raw  {"name":"First event","start_time":"2023-07-01 15:00:00","end_time":"2023-07-01 16:00:00"}
        $event = Event::create([
            ...$request->validate([  //validate to check correct and existing fields before create the event
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',   //nulable means optional
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]),
            'user_id' => $request->user()->id
        ]);
        return new EventResource($this->loadRelationships($event));  //upddates thanks to loadRelationships() + correct json format x response
    }


    public function show(Event $event)
    {  #Postman GET url http://127.0.0.1:8000/api/events/2
        return new EventResource(    //upddates thanks to loadRelationships() + correct json format x response
            $this->loadRelationships($event)
        );
    }


    public function update(Request $request, Event $event)
    {       # use Postman software with PUT url http://127.0.0.1:8000/api/events/2  + body raw {"name": "My edited Text!"}
        // if(Gate::denies('update-event', $event)){
        //     abort(403, 'You are not authorized to update this event.');
        // }
        //$this->authorize('update-event', $event);  //ora usa la invece la policy, questo fa la stessa identica cosa del code sopra commentato, ha bisogno di use AuthorizesRequests;

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after:start_time',
            ])
        );
        return new EventResource($this->loadRelationships($event));  //upddates thanks to loadRelationships() + correct json format x response
    }


    public function destroy(Event $event)
    {   #Postman DELETE url http://127.0.0.1:8000/api/events/2
        $event->delete();
        // return response()->json([
        //     'message' => 'Event deleted successfully'  #x debug
        // ]);
        return response()->noContent(); //noContent() operation done, no content to return anymore(status 204)
    }
}
