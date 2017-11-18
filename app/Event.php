<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    use Searchable;

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

    /**
     * Date formats;
     * https://www.elastic.co/guide/en/elasticsearch/reference/current/date.html
     */
    public function toSearchableArray(): array
    {
        return [
            'title'     => $this->title,
            'type'      => $this->type,
            'datetime'  => date('Y-m-d\TH:i:s\Z', strtotime($this->start_date .' '. $this->registration_start_time)),
        ];
    }
}
