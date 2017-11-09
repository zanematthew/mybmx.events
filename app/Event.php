<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    use Searchable;

    protected $indexConfigurator = EventIndexConfigurator::class;

    protected $searchRules = [
        //
    ];

    // Here you can specify a mapping for a model fields.
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'string',
                'fields' => [
                    'raw' => [
                        'type' => 'string',
                        'index' => 'not_analyzed',
                    ]
                ]
            ],
            'type' => [
                'type' => 'string',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                        'index' => 'not_analyzed',
                    ]
                ]
            ],
            'start_date' => [
                'type' => 'date',
            ]
        ]
    ];

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
