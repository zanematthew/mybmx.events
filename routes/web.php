<?php

Route::get('/test-search', function(){
// GET /test_index/_search
// {
//   "query" : {
//     "bool": {
//       "must": [
//         {
//           "match": {
//             "z_type": "venue"
//           }
//         }
//       ]
//     }
//   },
//   "sort" : [
//         {
//             "_geo_distance" : {
//                 "latlon" : "38.6364668,-77.2934339",
//                 "order" : "asc",
//                 "unit" : "km",
//                 "mode" : "min",
//                 "distance_type" : "arc"
//             }
//         }
//     ],
//     "size": 50
// }
// 38.6364668,-77.2934339; // Woodbridge, VA
// 39.2628271,-76.6350047; // MD

    $r = App\Venue::search('', function ($engine, $query, $options) {
        $options['body']['sort']['_geo_distance'] = [
                'latlon'        => '39.2628271,-76.6350047',
                'order'         => 'asc',
                'unit'          => 'mi',
                'mode'          => 'min',
                'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->where('z_type', 'venue')->take(10)->get()->pluck('name')->toArray();
    dd($r);

    $results = App\Venue::search('', function($engine, $query, $options) {
        // $options['body']['query']['bool']['must'][] = [
        //     'match_all' => new \stdClass()
        // ];
        $options['body']['sort']['_geo_distance'] = [
            'latlon' => '39.2628271,-76.6350047', // VA
            'order'         => 'asc',
            'unit'          => 'km',
            'mode'          => 'min',
            'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->take(300)->get()->pluck('name');
    echo '<pre>';
    print_r($results);
    die();

    // Closest venues
    $results = App\Venue::search('', function($engine, $query, $options) {
        $options['body']['query']['bool']['filter'] = [
            'range' => [
                'registration' => [
                    'gte'=> Illuminate\Support\Carbon::today()->toDateString(),
                ]
            ]
        ];
        $options['body']['sort']['_geo_distance'] = [
            'latlon'        => '39.2628271,-76.6350047',
            // 'latlon' => '38.6364668,-77.2934339', // VA
            'order'         => 'asc',
            'unit'          => 'km',
            'mode'          => 'min',
            'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->take(20)->get()->load('venue.city.states')->toArray();
    dd($results);

    // Closets events; from this weekend
    $results = App\Event::search('', function($engine, $query, $options) {
        $options['body']['query']['bool']['filter'] = [
            'range' => [
                'registration' => [
                    'gte'=> Illuminate\Support\Carbon::today()->toDateString(),
                ]
            ]
        ];
        $options['body']['sort']['_geo_distance'] = [
            'latlon'        => '39.2628271,-76.6350047',
            // 'latlon' => '38.6364668,-77.2934339', // VA
            'order'         => 'asc',
            'unit'          => 'km',
            'mode'          => 'min',
            'distance_type' => 'arc',
        ];
        return $engine->search($options);
    })->take(20)->get()->load('venue.city.states')->toArray();
    dd($results);

    // Keyword, sorted by closest;
    // $results = App\Venue::search('chester', function($engine, $query, $options) {
    //     $options['body']['sort']['_geo_distance'] = [
    //         'latlon'        => '39.2628271,-76.6350047', // Baltimore, MD
    //         'order'         => 'asc',
    //         'unit'          => 'km',
    //         'mode'          => 'min',
    //         'distance_type' => 'arc',
    //     ];
    //     return $engine->search($options);
    // })->take(100)->get()->load('city.states')->pluck('name');
    // dd($results);

    // Just distance, sorted by closets
    // https://www.elastic.co/guide/en/elasticsearch/reference/5.4/search-request-sort.html
    // $foo = App\Venue::search('bmx')->take(200)->get()->pluck('name');
    // dd($foo);

    // $results = App\Venue::search('', function($engine, $query, $options) {
        // $options['body']['query']['bool']['filter']['geo_distance'] = [
        //     'distance' => '500mi',
        //     'latlon'   => '39.2628271,-76.6350047',
        // ];
        // $options['body']['sort']['_geo_distance'] = [
        //     'latlon'        => '39.2628271,-76.6350047',
        //     // 'latlon'        => '39.590010,-119.245782',
        //     'order'         => 'asc',
        //     'unit'          => 'km',
        //     'mode'          => 'min',
        //     'distance_type' => 'arc',
        // ];
    //     return $engine->search($options);
    // })->take(4)->get()->load('city.states')->pluck('name');
    // dd($results);
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
