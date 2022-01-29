<?php
/**
*
*
*
******************* NEW shop
*
*
*
*/

/**
 *  Argon Backend ROUTE
 */

Route::prefix('{slug}')->middleware(['argonbackend'])->group(function () {
    Route::namespace('Backend\Argon')->group(function () {
        Route::get('login', [
            'as' => 'argon.auth.login',
            'uses' => 'AuthController@getLogin'
        ]);
        Route::post('login', [
            'as' => 'argon.auth.login.post',
            'uses' => 'AuthController@postLogin'
        ]);
    });
});


Route::prefix('{slug}')->middleware(['auth'])->group(function () {
	Route::namespace('Backend\Argon')->group(function () {
        Route::get('logout', [
            'as' => 'argon.auth.logout',
            'uses' => 'Auth\AuthController@getLogout'
        ]);

        Route::get('/', [
            'as' => 'argon.dashboard',
            'uses' => 'DashboardController@index',
            //
        ]);


    });
});

?>