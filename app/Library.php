<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    public function user()
    {
        return $this->belongsToOne('App\User');
    }
}
