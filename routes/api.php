<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/event/{id}/{slug?}', function ($id, $slug = '') {
    return App\Event::with('venue.city.states')->where('id', $id)->first();
})->name('event.single');

/*
|--------------------------------------------------------------------------
| Events Routes
|--------------------------------------------------------------------------
|
| All events routes are prefixed with [/api/]events. URL constraints are
| located in `RouteServiceProvider.php`.
|
| events/, state/, and type/ accept a boolean query parameter of;
| this_month: All events from today until the end of the month
| next_month: All events next month
| upcoming: All events from today into the future
*/
Route::group(['prefix' => 'events'], function () {

    Route::get('/', 'EventController@index')->name('events.index');
    Route::get('/{state}', 'EventController@state')->name('events.state');
    Route::get('/{type}', 'EventController@type')->name('events.type');

    Route::get('/{year}', 'EventController@year')->name('events.year');
    Route::get('/{year}/{month}', 'EventController@yearMonth')->name('events.year.month');
    Route::get('/{year}/{type}', 'EventController@yearType')->name('events.year.type');
    Route::get('/{year}/{month}/{type}', 'EventController@yearMonthType')->name('events.year.month.type');

    Route::get('/{year}/{state}', 'EventController@yearState')->name('events.year.state');
    Route::get('/{year}/{type}/{state}', 'EventController@yearTypeState')->name('events.year.type.state');
    Route::get('/{year}/{month}/{state}', 'EventController@yearMonthState')->name('events.year.month.state');
    Route::get('/{year}/{month}/{type}/{state}', 'EventController@yearMonthTypeState')->name('events.year.month.type.state');
});

Route::get('/venue/{id}/{slug?}', function ($id, $slug = '') {
    return \App\Venue::with('events')->where('id', $id)->get();
})->name('venue.single');

Route::get('venues/{state?}', function ($state = '') {
    if ($state) {
        return App\Venue::with('events')->whereHas('city.states', function ($query) use ($state) {
            $query->where('abbr', strtoupper($state));
        })->paginate(10);
    }
    return App\Venue::with('events')->paginate(10);
})->name('venues.state');
