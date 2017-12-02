<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

abstract class SearchController extends Controller
{
    public $distance;
    public $z_type;
    public $take;

    public function __construct($z_type)
    {
        $this->distance      = '2000mi';
        $this->z_type        = $z_type;
        $this->take          = 20;
        $this->defaultLatLon = '39.290385,-76.612189'; // Baltimore, MD
    }
}
