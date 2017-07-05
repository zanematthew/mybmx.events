<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = [
        'title',
        'type',
        'url',
        'fee',
        'registration_start_time',
        'registration_end_time',
        'start_date',
        'end_date',
        'flyer_uri',
        'event_schedule_uri',
        'hotel_uri',
        'usabmx_track_id',
        'usabmx_id',
    ];

    protected $appends = [
        'slug',
        'type_name',
    ];

    public function schedules()
    {
        return $this->belongsToMany('App\Schedule');
    }

    public function venue()
    {
        return $this->belongsTo('App\Venue', 'venue_id');
    }

    public function getSlugAttribute()
    {
        return str_slug($this->title);
    }

    public function getTypeNameAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->type));
    }
}
