<?php
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
        ])->with('venue.city.states')->orderBy('start_date','asc')->limit(25)->get();

        return response()->json($results);
    }
}