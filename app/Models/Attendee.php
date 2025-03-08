<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);  //1 User belongs to 1 user, lrv search fk user_id in tab attendees
    }
    public function event():BelongsTo{
        return $this->belongsTo(Event::class);  //1 Attendee belongs to 1 Event, lrv search event_id in tab attendees
    }
}
