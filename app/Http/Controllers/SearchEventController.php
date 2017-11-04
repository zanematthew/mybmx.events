<?php

// @todo
//
// General Search Notes
//
// There will be three types of searches;
//
// Places -- Fields; zip, city, state
// Events -- Fields: Title, type, start date
// Venues -- Fields: Name
//
// Or just go straight to Elastic search and have the "type"
// adjust the "boost" accordingly.
namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends Controller
{
    // Search fields;
    // title (string)
    // type (string)
    // fee (numeric)
    // start date (numeric)
    protected function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $results = Event::where([
            ['start_date', '>=', Carbon::today()->toDateTimeString()],
            ['title', 'like', "%{$request->keyword}%"],
        ])->with('venue.city.states')->orderBy('start_date','asc')->limit(5)->get();

        return response()->json($results);
    }
}