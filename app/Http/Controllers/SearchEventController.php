<?php

namespace App\Http\Controllers;

use App\Event;
use App\SearchInterface;
use App\AbstractSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends AbstractSearch implements SearchInterface
{
    public function text($text): \Illuminate\Http\JsonResponse
    {
        return response()->json(Event::search($text)
            ->where('z_type', 'event')
            ->take($this->take)
            ->get()
            ->load('venue.city.states'));
    }

    /**
     * Search for an Event by text;
     *   - filtered by Events greater than today
     *   - sorted by geo location
     *
     * @todo   needs test
     * @param  String $text    Search text.
     * @param  String $latlon  Geo-point as a string.
     * @return Object[         HTTP Json response
     */
    public function textProximity($text, $latlon): \Illuminate\Http\JsonResponse
    {
        // Filter gte than today
        // Sorted by date ASC
        //
        $results = Event::search($text, function($engine, $query, $options) use ($latlon) {
            $options['body']['query']['bool']['filter'] = [
                'range' => [
                    'registration' => [
                        'gte'=> Carbon::today()->toDateString()
                    ]
                ],
                '_geo_distance' => [
                    'latlon'        => $latlon,
                    'order'         => 'asc',
                    'unit'          => 'km',
                    'mode'          => 'min',
                    'distance_type' => 'arc',
                ]
            ];

            $options['body']['sort']['registration'] = [
                'order' => 'asc'
            ];
            return $engine->search($options);
        })->where('z_type', 'event')->take($this->take)->get()->load('venue.city.states');

        return response()->json($results);
    }

    /**
     * Sorted by distance, filtered by events from today.
     *
     * @todo   needs test
     * @param  String $latlon Geo-point as a string
     * @return Object         HTTP Json response
     */
    public function proximity($latlon): \Illuminate\Http\JsonResponse
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
        })->where('z_type', 'event')->take($this->take)->get()->load('venue.city.states');

        return response()->json($results);
    }
}