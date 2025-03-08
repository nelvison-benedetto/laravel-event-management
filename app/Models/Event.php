<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;  //abilitate generation of fakes using factory
    protected $fillable = ['name','description','start_time','end_time','user_id'];
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);  //1 Event belongs to 1 User, lrv search fk user_id in tab events
    }
    public function attendees(): HasMany{
        return $this->hasMany(Attendee::class);  //1 Event has many Attendees, lrv use event_id to associate
    }
}
