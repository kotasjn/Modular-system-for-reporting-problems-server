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

// tato cesta se použije pouze na produkčním serveru WEDOS, jelikož zde není přístup k příkazové řádce, je nutné příkazy spouštět přes PHP
//Route::get('b53c0d111e978eb6ffd484179e31f330', 'ArtisanController@passportInstall');

// pravidla ochrany dat pro android aplikaci
Route::view('/privacy_policy', 'privacy');

// všechny cesty se přesměrují na soubor main.blade.php, který obsahuje hlavní komponentu Vue.js
Route::get('{any}', function () {
    return view('main');
})->where('any', '.*');