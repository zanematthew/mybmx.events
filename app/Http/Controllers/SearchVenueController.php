<?php

namespace App\Http\Controllers;

use App\Venue;
use App\SearchInterface;
use App\AbstractSearch;

class SearchVenueController extends AbstractSearch implements SearchInterface
{

    /**
     * A simple full text search.
     *
     * @todo   needs test
     * @param  String $text Search text
     * @return Object       HTTP Json response.
     */
    public function phrase($text, $latlon): \Illuminate\Http\JsonResponse
    {
        $client = \Elasticsearch\ClientBuilder::create()->build();
        $response = $client->search([
            'index' => env('ELASTICSEARCH_INDEX'),
            'type' => 'doc',
                'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['z_type' => [ 'value' => 'venue' ]]],
                            ['match_phrase_prefix' => ['name' => $text]]
                        ]
                    ]
                ],
            ],
        ]);
        return response()->json($this->formatResults($response));
    }

    /**
     * Sort search by proximity.
     *
     * @todo   needs test
     * @param  String $latlon A geo-point as a string.
     * @return Object         HTTP Json response.
     */
    public function currentLocation($latlon): \Illuminate\Http\JsonResponse
    {
        $latlonArray = $this->latlonAsArray($latlon);

        $client = \Elasticsearch\ClientBuilder::create()->build();
        $response = $client->search([
        'index' => env('ELASTICSEARCH_INDEX'),
            'type' => 'doc',
                'body' => [
                'query' => [
                    'bool' => [
                        'must' => [ [ 'match_all' => new \stdClass() ] ],
                        'filter' => [
                            'geo_distance' => [
                                'distance' => '200mi',
                                'latlon' => $latlon
                            ]
                        ],
                        'should' => [
                            [ 'term' => ['z_type' => ['value' => 'venue' ] ] ]
                        ],
                        'minimum_should_match' => 1
                    ]
                ],
                'sort' => [
                    [
                        '_geo_distance' => [
                            'latlon' => $latlon,
                            'order' => 'asc'
                        ]
                    ]
                ],
                '_source' => true,
                'script_fields' => [
                    'distance_from' => [
                        'script' => [
                            'source' => 'doc[\'latlon\'].arcDistance(params.lat,params.lon) * 0.001',
                            'lang' => 'painless',
                            'params' => [
                                'lat' => $latlonArray['lat'],
                                'lon' => $latlonArray['lon'],
                            ]
                        ]
                    ]
                ]
            ],
        ]);

        $results = $this->formatResults($response);
        return response()->json($results);
    }
}
