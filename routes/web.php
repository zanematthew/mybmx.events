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
})->name('event.single');

//
// All events
// This is the only non-date based query we are allowing.
// /
// /state/

//
// All year based on events
//
// /year/
// /year/month/

// /year/type/
// /year/month/type/

// /year/state?/
// /year/month/state?/

// /year/type/state?/
// /year/month/type/state?/
Route::group(['prefix' => 'events'], function () {
    // /
    Route::get('/', function () {
        return App\Event::with('venue.city.states')->paginate(10);
    })->name('events');

    // /state/
    Route::get('/{state?}', function ($state = '') {
        return App\Event::with('venue.city.states')
            ->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            })->paginate(10);
    })->name('events.state');

    //
    // All year based on events
    //

    // /year/
    // /year/state?/
    Route::get('{year}/{state?}', function ($year, $state = '') {
        if ($state) {
            return App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->whereHas('venue.city.states', function ($query) use ($state) {
                    $query->where('abbr', strtoupper($state));
                })->paginate(10);
        }
        return App\Event::with('venue.city.states')->whereYear('start_date', $year)->paginate(10);
    })->name('events.year.state');

    // /year/month/
    // /year/month/state?/
    Route::get('/{year}/{month}/{state?}', function ($year, $month, $state = '') {
        if ($state) {
            return App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->whereHas('venue.city.states', function ($query) use ($state) {
                    $query->where('abbr', strtoupper($state));
                })->paginate(10);
        }
        return App\Event::with('venue.city.states')
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->paginate(10);
    })->name('events.year.month.state');

    // /year/type/
    // /year/type/state?/
    Route::get('/{year}/{type}/{state?}', function ($year, $type, $state = '') {
        if ($state) {
            return App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->where('type', $type)
                ->whereHas('venue.city.states', function ($query) use ($state) {
                    $query->where('abbr', strtoupper($state));
                })->paginate(10);
        }
        return App\Event::with('venue.city.states')->whereYear('start_date', $year)->where('type', $type)->paginate(10);
    })->name('events.year.type.state');

    // /year/month/type/
    // /year/month/type/state?/
    Route::get('/{year}/{month}/{type}/{state?}', function ($year, $month, $type, $state = '') {

        if ($state) {
            return App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->where('type', $type)
                ->whereHas('venue.city.states', function ($query) use ($state) {
                    $query->where('abbr', strtoupper($state));
                })
                ->paginate(10);
        }
        return App\Event::with('venue.city.states')
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->where('type', $type)
            ->paginate(10);
    })->name('events.year.month.type.state');
});
