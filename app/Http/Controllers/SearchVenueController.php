<?php

namespace App\Http\Controllers;

use App\Venue;
use Illuminate\Http\Request;

class SearchVenueController extends SearchController
{
    public function __construct()
    {
        parent::__construct('venue');
    }

    protected function index(Request $request)
    {
        $latlon = $request->latlon ?? $this->defaultLatLon;

        if ($request->text === 'Current Location') {
            return $this->distance($latlon);
        }
        if ($request->text) {
            return $this->text($request->text);
        }
    }

    protected function text(String $text): \Illuminate\Http\JsonResponse
    {
        $results = Venue::search($text)->where('z_type', $this->z_type)->take($this->take)->get();

        // If no results, provide option to search by current location only.
        if (empty($results->toArray())) {
            return response()->json([]);
        }

        return response()->json($results->load('city.states'));
    }

    // Just distance, sorted by closets
    // https://www.elastic.co/guide/en/elasticsearch/reference/5.4/search-request-sort.html
    // Search by location, then sort by distance.
    // Default is to show all venues that are closest to current location.
    // https://www.elastic.co/guide/en/elasticsearch/reference/5.4/search-request-sort.html
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
        })->where('z_type', $this->z_type)->take($this->take)->get()->load('city.states');

        return response()->json($results);
    }
}
