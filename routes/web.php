<?php

Route::get('/test-search', function(){

    // ES Version: 5.6
    // Events by type
    // Closets events; from this weekend
    $results = App\Event::search('', function($engine, $query, $options) {
        $options['body']['query']['bool']['filter'] = [
            'range' => ['datetime' => [
                'gte'=> Illuminate\Support\Carbon::today()->toDateString(),
            ]]
        ];
        $options['body']['sort']['_geo_distance'] = [
            'latlon'        => '39.290385,-76.612189',
            'order'         => 'asc',
            'unit'          => 'km',
            'mode'          => 'min',
            'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->take(20)->get()->load('venue.city.states')->toArray();
    dd($results);

    // Keyword, sorted by closest;
    // $results = App\Venue::search('new york', function($engine, $query, $options) {
    //     $options['body']['sort']['_geo_distance'] = [
    //         'latlon'        => '39.290385,-76.612189',
    //         'order'         => 'asc',
    //         'unit'          => 'km',
    //         'mode'          => 'min',
    //         'distance_type' => 'arc',
    //     ];
    //     return $engine->search($options);
    // })->take(100)->get()->load('city.states')->pluck('name');
    // dd($results);

    // Just distance
    // https://www.elastic.co/guide/en/elasticsearch/reference/5.4/search-request-sort.html
    $results = App\Venue::search('', function($engine, $query, $options) {
        $options['body']['query']['bool']['filter']['geo_distance'] = [
            'distance' => '500km',
            'latlon'   => ['lat' => 37.7045743, 'lon' => -122.2010459],
        ];
        $options['body']['sort']['_geo_distance'] = [
            'latlon'        => '37.7045743,-122.2010459',
            'order'         => 'asc',
            'unit'          => 'km',
            'mode'          => 'min',
            'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->take(20)->get()->load('city.states')->pluck('name');
    dd($results);

    // Event keyword
    $results = App\Event::search('race')
           // ->where('start_date', '>=', Illuminate\Support\Carbon::today()->toDateString())
           ->where('start_date', '>', '2017-11-01')
           ->take(10)
           ->get()
           ->load('venue.city.states');
    dd($results);
});

// Route::get('/geolocation', function() {
//     dd(app('geocoder')->reverse(40.911488,-73.782355)->get()->first());
//     // dd(app('geocoder')->geocode('New Rochelle, NY')->get());
//     // dd(app('geocoder')->geocode('8.8.8.8')->get());
// });

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Default Auth Routes
|--------------------------------------------------------------------------
|
| Register auth routes that are built with:
|     `php artisan make auth`, i.e.,
|     /login
|     /register
|     /password/reset
|
*/
Auth::routes();

// This is the default route to manage API keys generated via
// Laravel Passport.
// @todo remove at some point.
// Route::get('/home', 'HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| Socialite
|--------------------------------------------------------------------------
|
| The following routes handle the redirect for successfully authenticated
| users. The `http://example.com/redirect/` route determines which
| "provider" to use, i.e., Facebook, Twitter, etc. currently
| supporting Facebook only.
|
| The `http://example.com/callback/` route will create or get the
| approved user.
|
*/
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');

/*
|--------------------------------------------------------------------------
| Vue
|--------------------------------------------------------------------------
|
| Global catch all for any routes that do not meet THE ABOVE ROUTES! From
| here, Vue.js + vue-router will handle all "web" request.
|
| Note, see `/routes/api.php` for API specific routing.
|
*/
Route::get('/{vue?}', function () {
    return view('vue');
})->middleware('auth');
