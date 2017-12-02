<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends SearchController
{
    public function __construct()
    {
        parent::__construct('event');
    }

    // Text search
    // Date greater than today
    // Sorted by proximity
    protected function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $latlon = $request->latlon ?? $this->defaultLatLon;

        if ($request->text === 'Current Location') {
            return $this->distance($latlon);
        }
        if ($request->text) {
            return $this->text($request->text, $latlon);
        }
    }

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
        })->where('z_type', $this->z_type)->take($this->take)->get()->load('venue.city.states');

        return response()->json($results);
    }

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
        })->where('z_type', $this->z_type)->take($this->take)->get()->load('venue.city.states');

        return response()->json($results);
    }
}