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

use Illuminate\Support\Facades\Route;

//Route::get('b53c0d111e978eb6ffd484179e31f330', 'ArtisanController@passportInstall');

Route::view('/privacy_policy', 'privacy');

Route::get('{any}', function () {
    return view('main');
})->where('any', '.*');