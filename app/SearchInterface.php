<?php

namespace App;

use Illuminate\Http\Request;

interface SearchInterface
{
    // The HTTP router, all request logic is handled here
    // this is called on the router level i.e.,
    // Route::get('/search/{text}/', 'SeachEvent@index');
    public function index(Request $request): \Illuminate\Http\JsonResponse;

    // Simple text based search
    public function text($text): \Illuminate\Http\JsonResponse;

    // Text search with proximity
    public function textProximity($text, $latlon): \Illuminate\Http\JsonResponse;

    // Proximity only
    public function proximity($latlon): \Illuminate\Http\JsonResponse;
}