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
     * The allowed keys that are being searched, and the
     * any formatting if needed.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'title'       => null,
            'name'        => $this->name,
            'zip_code'    => $this->zip_code,
            'latitude'    => $this->lat,
            'longitude'   => $this->long,
            'latlon'      => sprintf('%f,%f', $this->lat, $this->long),
            'city'        => $this->city->name,
            'state'       => $this->city->states()->first()->name ?? null,
            'z_type'      => 'venue',
            'created_at'  => date('Y-m-d\TH:i:s\Z', time()),
        ];
    }

    public static function elasticsearchMapping()
    {
        return [
            'title'        => ['type' => 'text'],
            'name'        => ['type' => 'text'],
            'zip_code'    => ['type' => 'integer'],
            'latitude'    => ['type' => 'float'],
            'longitude'   => ['type' => 'float'],
            'latlon'      => ['type' => 'geo_point'],
            'city'        => ['type' => 'keyword'],
            'state'       => ['type' => 'keyword'],
            'z_type'      => ['type' => 'keyword'],
            'created_at'  => ['type' => 'date'],
        ];
    }
}