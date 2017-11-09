<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use Searchable;

    protected $indexConfigurator = EventIndexConfigurator::class;

    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'fields' => [
                    'raw' => [
                        'type' => 'string',
                        'index' => 'not_analyzed',
                    ]
                ]
            ],
        ]
    ];

    protected $fillable = [
        'name',
        'district',
        'usabmx_id',
        'website',
        'image_uri',
        'description',
        'street_address',
        'lat',
        'long',
        'city_id',
        'zip_code',
        'email',
        'primary_contact',
        'phone_number',
    ];

    protected $appends = [
        'slug',
    ];

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function getSlugAttribute()
    {
        return str_slug($this->name);
    }
}
