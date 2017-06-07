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

Route::get('/events/{event}', function (App\Event $event) {
    return view('welcome', App\Event::with('venue.city')->where('id', $event->id)->first()->toArray());
})->name('event.single');
