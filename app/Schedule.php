<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'default',
    ];

    protected $appends = [
        'slug'
    ];

    public function user()
    {
        // Many Schedules belong to many Users
        // note, select is used later for queries.
        return $this->belongsTo('App\User')->select(['id','name']);
    }

    public function events()
    {
        // One Schedule has many Events
        return $this->belongsToMany('App\Event')->withPivot([
            'created_at',
            'updated_at',
        ]);
    }

    public function getSlugAttribute()
    {
        return str_slug($this->name);
    }
}
