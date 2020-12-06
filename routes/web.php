<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events', 'EventsController@index');
Route::post('/events', 'EventsController@save_event');