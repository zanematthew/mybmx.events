<?php

namespace App\Http\Controllers;

use App\Venue;
use Illuminate\Http\Request;

class SearchVenueController extends Controller
{
    private $distance;
    private $z_type;
    private $take;

    public function __construct()
    {
        $this->distance = '2000mi';
        $this->z_type   = 'venue';
        $this->take     = 20;
    }

    protected function index(Request $request)
    {
        $latlon = $request->latlon ?? '39.290385,-76.612189'; // Baltimore, MD

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
        $results = Venue::search('', function ($engine, $query, $options) {
            $options['body']['sort']['_geo_distance'] = [
                'latlon'        => '39.2628271,-76.6350047',
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
