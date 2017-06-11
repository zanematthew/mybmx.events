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

Route::group(['prefix' => 'events'], function () {
    /**
     * The following routes are defined below. All are prefixed with "events",
     * and "state" is optional for all. See the RouteServiceProvider.php for
     * the regular expression constraints for each parameter.
     *
     * year/{state?}/
     * year/month/{state?}/
     * year/type/{state?}/
     * year/month/type/{state?}/
     */
    Route::get('/{state?}', function ($state = '') {
        $q = App\Event::with('venue.city.states');
        if ($state) {
            $q = $q->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            });
        }
        return $q->paginate(10);
    })->name('events.state');

    Route::get('{year}/{state?}', function ($year, $state = '') {
        $q = App\Event::with('venue.city.states')->whereYear('start_date', $year);
        if ($state) {
            $q = $q->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            });
        }
        return $q->paginate(10);
    })->name('events.year.state');

    Route::get('/{year}/{month}/{state?}', function ($year, $month, $state = '') {
        $q = App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month);
        if ($state) {
            $q = $q->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            });
        }
        return $q->paginate(10);
    })->name('events.year.month.state');

    Route::get('/{year}/{type}/{state?}', function ($year, $type, $state = '') {
        $q = App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->where('type', $type);
        if ($state) {
            $q = $q->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            });
        }
        return $q->paginate(10);
    })->name('events.year.type.state');

    Route::get('/{year}/{month}/{type}/{state?}', function ($year, $month, $type, $state = '') {
        $q = App\Event::with('venue.city.states')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->where('type', $type);
        if ($state) {
            $q = $q->whereHas('venue.city.states', function ($query) use ($state) {
                $query->where('abbr', strtoupper($state));
            });
        }
        return $q->paginate(10);
    })->name('events.year.month.type.state');
});
