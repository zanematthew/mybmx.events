<?php

Route::get('/test-search', function(){
    dd(App\Event::search('race')->take(10)->get());
});

Route::get('/geolocation', function() {
    dd(app('geocoder')->reverse(40.911488,-73.782355)->get()->first());
    // dd(app('geocoder')->geocode('New Rochelle, NY')->get());
    // dd(app('geocoder')->geocode('8.8.8.8')->get());
});

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
