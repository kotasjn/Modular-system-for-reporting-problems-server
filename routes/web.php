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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/**
 * Auth routes with Socialite support
 */

Auth::routes();

Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');

Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','facebook|google');



Route::get('/', 'DashboardController@index')->name('dashboard');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
