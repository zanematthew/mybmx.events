<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends Controller
{
    /**
     * Filter the HTTP request here.
     *
     * @todo   needs test
     * @param  Request $request HTTP request variables.
     * @return Object       HTTP Json response.
     */
    protected function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $latlon = $request->latlon ?? '39.290385,-76.612189';

        if ($request->text === 'Current Location') {
            return $this->distance($latlon);
        }
        if ($request->text) {
            return $this->text($request->text, $latlon);
        }
    }

    /**
     * Search for an Event by text;
     *   - filtered by Events greater than today
     *   - sorted by geo location
     *
     * @todo   needs test
     * @param  String $text    Search text.
     * @param  String $latlong Geo-point as a string.
     * @return Object[         HTTP Json response
     */
    protected function text(String $text, String $latlong): \Illuminate\Http\JsonResponse
    {
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
        })->where('z_type', 'event')->take(20)->get()->load('venue.city.states');

        return response()->json($results);
    }

    /**
     * Sorted by distance, filtered by events from today.
     *
     * @todo   needs test
     * @param  String $latlon Geo-point as a string
     * @return Object         HTTP Json response
     */
    protected function distance(String $latlon): \Illuminate\Http\JsonResponse
    {
        $results = Event::search('', function ($engine, $query, $options) use ($latlon) {
            $options['body']['query']['bool']['filter'] = [
                'range' => ['registration' => [
                    'gte'=> Carbon::today()->toDateString(),
                ]]
            ];
            $options['body']['sort']['_geo_distance'] = [
                'latlon'        => $latlon,
                'order'         => 'asc',
                'unit'          => 'mi',
                'mode'          => 'min',
                'distance_type' => 'arc',
            ];
            return $engine->search($options);
        })->where('z_type', 'event')->take(20)->get()->load('venue.city.states');

        return response()->json($results);
    }
}