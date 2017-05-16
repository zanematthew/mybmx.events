<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $fillable = [
        'name',
        'abbr',
    ];

    public function cities()
    {
        // One State has many Cities
        return $this->hasMany('App\City');
    }
}
