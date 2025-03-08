<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array    //FOLDER 'RESOURCES' is used to define the CORRECT JSON format of API response
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user' => new UserResource($this->whenLoaded('user')),  //if the event has a field 'user' in the query, lrv use UserResource file x the field
            'attendees' => AttendeeResource::collection(
                $this->whenLoaded('attendees')  //if the event has a field 'attendees' in the query, lrv use AttendeeResource correct json format x response
            )
        ];
    }
}
