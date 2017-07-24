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

Route::group([
    'prefix'     => 'user',
    'middleware' => 'auth:api',
    ], function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });

    Route::get('/schedule/', 'ScheduleController@index')->name('user.schedule.index');
    Route::get('/schedule/master/event/ids/', 'ScheduleController@masterEventIds')
        ->name('user.schedule.master.event.ids');
    Route::get('/schedule/attending/events/master/', 'ScheduleController@attendingEventsMaster')
        ->name('user.schedule.attending.events.master');

    Route::post('/schedule/new/', 'ScheduleController@store')->name('user.schedule.store');
    Route::post('/schedule/{id}/update/', 'ScheduleController@update')->name('user.schedule.update');
    Route::post('/schedule/{id}/toggle-default/', 'ScheduleController@toggleDefault')->name('user.schedule.toggle.default');

    Route::post('/schedule/master/attend/{id}/', 'ScheduleController@masterAttend')->name('user.schedule.master.attend');

    Route::delete('/schedule/{id}/delete/', 'ScheduleController@delete')->name('user.schedule.delete');
});

Route::group(['prefix' => 'venue'], function () {
    Route::get('/{id}/{slug?}', 'VenueController@single')->name('venue.single');
    Route::get('/events/{id}', 'VenueController@events')->name('venue.events');
});

Route::group(['prefix' => 'venues'], function () {
    Route::get('/{state}', 'VenueController@state')->name('venues.state');
    Route::get('/', 'VenueController@index')->name('venues');
});

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
Route::get('/event/{id}/{slug?}', function ($id, $slug = '') {
    return App\Event::with('venue.city.states')->where('id', $id)->first();
})->name('event.single');

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

Route::get('/states', function () {
    return response()->json(\App\State::select('name','abbr')->orderBy('name')->get());
});