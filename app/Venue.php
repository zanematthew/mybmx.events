<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
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
        return $this->belongsTo('App\city');
    }

    public function getSlugAttribute()
    {
        return str_slug($this->name);
    }
}
