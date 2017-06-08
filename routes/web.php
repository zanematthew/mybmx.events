<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/event/{id}/{slug?}', function ($id, $slug = '') {
    return response()->json(App\Event::with('venue.city')->where('id', $id)->first());
})->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])->name('event.single');

Route::group(['prefix' => 'api/events'], function () {
    Route::get('/', function () {
        return App\Event::with('venue.city.states')->paginate(10);
    })->name('events');

    Route::get('/{state?}', function ($state = '') {
        return App\Event::with('venue.city.states')->whereHas('venue.city.states', function ($query) use ($state) {
            $query->where('abbr', strtoupper($state));
        })->paginate(10);
    })->name('events.state');
});
