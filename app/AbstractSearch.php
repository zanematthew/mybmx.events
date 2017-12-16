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
        if ($request->text === 'Current Location') {
            $latlon = $request->latlon ?? '32.1561235,-111.0238915';
            return $this->currentLocation($latlon);
        } elseif ($request->text && $request->latlon) {
            return $this->textCurrentLocation($request->text, $request->latlon);
        } elseif ($request->text) {
            return $this->text($request->text);
        } elseif ($request->latlon) {
            return $this->currentLocation($request->latlon);
        } else {
            return response()->json([]);
        }
    }
}