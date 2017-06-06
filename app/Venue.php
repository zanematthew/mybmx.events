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
        'streetAddress',
        'lat',
        'long',
        'city_id',
        'zipCode',
        'email',
        'primaryContact',
        'phoneNumber',
    ];

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function city()
    {
        return $this->belongsTo('App\city');
    }
}
