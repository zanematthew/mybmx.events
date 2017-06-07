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

Route::get('/events/', function (App\Event $event) {
    return App\Event::with('venue.city')->paginate(10);
})->name('events');
