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


Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'Api\Auth\AuthController@login');
    Route::post('register', 'Api\Auth\AuthController@register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Api\Auth\AuthController@logout');
        Route::get('user', 'Api\Auth\AuthController@user');
    });
});

// Web API

Route::group(['middleware' => 'auth:api'], function () {

    Route::apiResource('/territories', 'TerritoryController');
    Route::apiResource('/territories/{territory}/reports', 'ReportController');
    Route::apiResource('/territories/{territory}/users', 'ModuleController');

    Route::apiResource('/territories/{territory}/modules', 'ModuleController');
    Route::put('/territories/{territory}/modules/{module}/activate', 'ModuleController@activate');

    Route::apiResource('/territories/{territory}/employees', 'EmployeeController');

    Route::get('/territories/{territory}/search', 'UserSearchController@search');

});


// mobile API

Route::group(['prefix' => 'mobile', 'middleware' => 'auth:api'], function () {

    // REPORT
    Route::post('/reports', 'Api\ReportController@reports');
    Route::post('/report', 'Api\ReportController@store');
    Route::get('/reports/{report}', 'Api\ReportController@show');
    Route::put('/reports/{report}', 'Api\ReportController@update');
    Route::delete('/reports/{report}', 'Api\ReportController@destroy');

    Route::apiResources([
        '/reports/{report}/likes' => 'Api\ReportLikeController',
        '/reports/{report}/comments' => 'Api\CommentController',
        '/reports/{report}/comments/{comment}/likes' => 'Api\CommentLikeController'
    ]);

    // MODULE
    Route::post('/modules', 'Api\ModuleController@getActiveModules');

    // MODULE DATA
    Route::post('/reports/{report}/module_data', 'Api\ModuleDataController@store');

    // USER
    Route::get('/users/{user}', 'Api\UserController@show');

});

