<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\Fb2Controller;
use App\Http\Controllers\Pages\OpdsController;


Route::get('/', function() {
    return view('welcome');
});

Route::get('/opds/root.xml', [OpdsController::class, 'root']);
Route::get('/book/{id}.fb2', [Fb2Controller::class, 'book']);
