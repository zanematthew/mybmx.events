<?php

namespace App\Http\Controllers;

use App\Venue;
use Illuminate\Http\Request;

class SearchVenueController extends Controller
{
    protected function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $results = Venue::search($request->keyword)
            ->take(10)
            ->get();

        return response()->json($results);
    }
}
