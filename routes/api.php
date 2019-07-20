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

// cesty pro autentizaci
Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'Api\Auth\AuthController@login');
    Route::post('register', 'Api\Auth\AuthController@register');

    // pro tyto cesty musí být uživatel přihlášen
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Api\Auth\AuthController@logout');
        Route::get('user', 'Api\Auth\AuthController@user');
    });

});

// uložení chybového hlášení
Route::post('bugs', 'BugController@store');

// Web API
// pro tyto cesty musí být uživatel přihlášen
Route::group(['middleware' => 'auth:api'], function () {

    // apiResource vytváří cesty pro index, show, store, update a delete
    Route::apiResource('/territories', 'TerritoryController'); // cesty samosprávy
    Route::apiResource('/territories/{territory}/reports', 'ReportController'); // cesty podnětu
    Route::apiResource('/territories/{territory}/modules', 'ModuleController'); // cesty modulu

    Route::put('/territories/{territory}/modules/{module}/activate', 'ModuleController@activate'); // aktivace/deaktivace modulu

    Route::apiResource('/territories/{territory}/employees', 'EmployeeController'); // cesty zaměstnance samosprávy

    Route::get('/territories/{territory}/search', 'UserSearchController@search'); // vyhledání budoucího nového zaměstnance samosprávy

    Route::get('bugs', 'BugController@index'); // získání chybových hlášení
});


// mobile API
// pro tyto cesty musí být uživatel přihlášen
Route::group(['prefix' => 'mobile', 'middleware' => 'auth:api'], function () {

    // cesty pro podněty
    Route::post('/reports', 'Api\ReportController@reports');
    Route::post('/report', 'Api\ReportController@store');
    Route::get('/reports/{report}', 'Api\ReportController@show');
    Route::put('/reports/{report}', 'Api\ReportController@update');
    Route::delete('/reports/{report}', 'Api\ReportController@destroy');

    // cesty pro lajky a komentáře
    Route::apiResources([
        '/reports/{report}/likes' => 'Api\ReportLikeController',
        '/reports/{report}/comments' => 'Api\CommentController',
        '/reports/{report}/comments/{comment}/likes' => 'Api\CommentLikeController'
    ]);

    // získání aktivovaných modulů v rámci jedné samosprávy
    Route::post('/modules', 'Api\ModuleController@getActiveModules');

    // uložení dat modulu pro daný podnět
    Route::post('/reports/{report}/module_data', 'Api\ModuleDataController@store');

    // získání dat uživatele
    Route::get('/users/{user}', 'Api\UserController@show');

});

