<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'track_id',
        'type',
    ];

    public function schedules()
    {
        return $this->belongsToMany('App\Schedule');
    }

    public function venues()
    {
        return $this->belongsTo('App\Venue', 'venue_id');
    }
}
