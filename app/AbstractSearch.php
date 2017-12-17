<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class AbstractSearch extends Controller
{
    public $take = 20;

    /**
     * Filter the HTTP request here.
     *
     * @todo   needs test
     * @param  Request $request HTTP request variables.
     * @return Object       HTTP Json response.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->text === 'Current Location' && $request->latlon || $request->latlon) {
            return $this->currentLocation($request->latlon);
        } elseif ($request->text) {
            return $this->phrase($request->text, $request->latlon);
        } else {
            return response()->json([]);
        }
    }

    // Append extra fields
    // format distance from
    public function getExtraFields($item): array
    {
        if ($item['fields']['distance_from']) {
            $item['fields']['distance_from'] = round($item['fields']['distance_from'][0]);
        }

        return $item['fields'];
    }

    public function hasExtraFields($item): bool
    {
        return (isset($item['fields'])) ? true : false;
    }

    public function latlonAsArray($latlon): array
    {
        list($lat, $lon) = explode(',', $latlon);
        return [
            'lat' => floatval($lat),
            'lon' => floatval($lon),
        ];
    }

    public function formatResults($response): array
    {
        $hits = [];
        if ($response) {
            $hits = array_map(function ($item) {
                if ($this->hasExtraFields($item)) {
                    return array_merge($item['_source'], $this->getExtraFields($item));
                }
                return $item['_source'];
            }, $response['hits']['hits']);
        }
        return $hits;
    }
}