<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{

    use Searchable;

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

    /**
     * The data from our model being to the search engine.
     * This will later need to mapped via an index.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name'        => $this->name,
            'website'     => $this->website,
            'description' => $this->description,
            'zip_code'    => $this->zip_code,
            'latitude'    => $this->lat,
            'longitude'   => $this->long,
            'latlon'      => sprintf('%f,%f', $this->lat, $this->long),
            'city'        => $this->city->name,
            'state'       => $this->city->states()->first()->name ?? null,
        ];
    }

    public static function elasticsearchMapping()
    {
        return [
            'name' => ['type' => 'text'],
            'website' => ['type' => 'text'],
            'description' => ['type' => 'text'],
            'zip_code' => ['type' => 'integer'],
            'latitude' => ['type' => 'float'],
            'longitude' => ['type' => 'float'],
            'latlon' => ['type' => 'geo_point'],
            'city' => ['type' => 'keyword'],
            'state' => ['type' => 'keyword'],
        ];
    }
}