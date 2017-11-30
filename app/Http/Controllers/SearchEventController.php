<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends Controller
{
    // Text search
    // Date greater than today
    // Sorted by proximity
    protected function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $text    = $request->text ?? '';
        $latlong = $request->latlong ?? '39.290385,-76.612189';

        $results = Event::search($text, function($engine, $query, $options) use ($latlong) {
            $options['body']['query']['bool']['filter'] = [
                'range' => ['registration' => [
                    'gte'=> Carbon::today()->toDateString(),
                ]]
            ];
            $options['body']['sort']['_geo_distance'] = [
                'latlon'        => $latlong,
                'order'         => 'asc',
                'unit'          => 'km',
                'mode'          => 'min',
                'distance_type' => 'arc',
            ];
            return $engine->search($options);
        })->take(20)->get()->load('venue.city.states');

        return response()->json($results);
    }

    // Closest search
    // Text search
}