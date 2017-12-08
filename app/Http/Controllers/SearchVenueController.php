<?php

namespace App\Http\Controllers;

use App\Venue;
use Illuminate\Http\Request;

class SearchVenueController extends Controller
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
        $latlon = $request->latlon ?? '39.290385,-76.612189'; // Baltimore, MD

        if ($request->text === 'Current Location') {
            return $this->distance($latlon);
        }
        if ($request->text) {
            return $this->text($request->text);
        }
        return response()->json([]);
    }

    /**
     * A simple full text search.
     *
     * @todo   needs test
     * @param  String $text Search text
     * @return Object       HTTP Json response.
     */
    protected function text(String $text): \Illuminate\Http\JsonResponse
    {
        return response()->json(Venue::search($text)
            ->where('z_type', 'venue')
            ->take(20)
            ->get()
            ->load('city.states'));
    }

    /**
     * Sort search by distance.
     *
     * @todo   needs test
     * @param  String $latlon A geo-point as a string.
     * @return Object         HTTP Json response.
     */
    protected function distance(String $latlon): \Illuminate\Http\JsonResponse
    {
        $results = Venue::search('', function ($engine, $query, $options) use ($latlon) {
            $options['body']['sort']['_geo_distance'] = [
                'latlon'        => $latlon,
                'order'         => 'asc',
                'unit'          => 'mi',
                'mode'          => 'min',
                'distance_type' => 'arc',
            ];
            return $engine->search($options);
        })->where('z_type', 'venue')->take(20)->get()->load('city.states');

        return response()->json($results);
    }
}
