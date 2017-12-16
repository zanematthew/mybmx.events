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
    public function text($text): \Illuminate\Http\JsonResponse
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

    // Append extra fields
    // format distance from
    public function maybeAppendExtraFieldsToSource($item): array
    {
        if (empty($item['fields'])) {
            return $item;
        }

        if ($item['fields']['distance_from']) {
            $item['fields']['distance_from'] = round($item['fields']['distance_from'][0]);
        }

        return array_merge($item['_source'], $item['fields']);
    }

    public function formatResults($response): array
    {
        $hits = [];
        if ($response) {
            $hits = array_map(function ($item) {
                $item['_source'] = $this->maybeAppendExtraFieldsToSource($item);
                return $item['_source'];
            }, $response['hits']['hits']);
        }
        return $hits;
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
        list($lat, $lon) = explode(',', $latlon);
        $lat = floatval($lat);
        $lon = floatval($lon);

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
                                'lat' => $lat,
                                'lon' => $lon,
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
