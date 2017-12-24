<?php

namespace App\Http\Controllers;

use App\Event;
use App\AbstractSearch;
use App\SearchInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchEventController extends AbstractSearch
{
    /**
     * Filter the HTTP request here.
     *
     * @todo   needs test
     * @param  Request $request HTTP request variables.
     * @return Object       HTTP Json response.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->phrase($request->text, $request->latlon);
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
    public function phrase($text, $latlon): \Illuminate\Http\JsonResponse
    {
        $latlonArray = $this->latlonAsArray($latlon);
        $client = \Elasticsearch\ClientBuilder::create()->build();
        $response = $client->search([
            'index' => env('ELASTICSEARCH_INDEX'),
            'type' => 'doc',
                'body' => [
                'query' => [
                'bool' => [
                    'must' => [
                        [
                            'multi_match' => [
                                'query' => $text,
                                'type' => 'phrase_prefix',
                                'fields' => ['title', 'type', 'city', 'state']
                            ]
                        ],
                        [
                            'range' => [
                                'registration' => [
                                    'gte' => 'now'
                                ]
                            ]
                        ]
                    ],
                    'should' => [
                        ['term' => [ 'z_type' => [ 'value' => 'event' ] ] ]],
                        'minimum_should_match' => 1,
                        'filter' => [
                            'geo_distance' => [
                                'distance' => '500mi',
                                'latlon' => $latlon
                            ]
                        ]
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

        return response()->json($this->formatResults($response));
    }

    public function suggestion(Request $request): \Illuminate\Http\JsonResponse
    {
        $latlonArray = $this->latlonAsArray($request->latlon);
        $client = \Elasticsearch\ClientBuilder::create()->build();
        $response = $client->search([
            'index' => env('ELASTICSEARCH_INDEX'),
            'type' => 'doc',
                'body' => [
                'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'registration' => [
                                    'gte' => 'now'
                                    ]
                                ]
                            ]
                        ],
                    'should' => [ ['term' => [ 'z_type' => [ 'value' => 'event' ] ] ]
                    ],
                    'filter' => [
                        'geo_distance' => [
                            'distance' => '1000mi',
                            'latlon' => $request->latlon
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    [
                        '_geo_distance' => [
                            'latlon' => $request->latlon,
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
                ],
                'size' => 4,
            ],
        ]);

        return response()->json($this->formatResults($response));
    }
}