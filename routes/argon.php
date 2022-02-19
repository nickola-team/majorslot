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
        ]);

        Route::get('/agent/list', [
            'as' => 'argon.agent.list',
            'uses' => 'UsersController@agent_list',
        ]);

        Route::get('/agent/child', [
            'as' => 'argon.agent.child',
            'uses' => 'UsersController@agent_child',
        ]);

        Route::get('/agent/transaction', [
            'as' => 'argon.agent.transaction',
            'uses' => 'UsersController@agent_transaction',
        ]);

        Route::get('/agent/rolling', [
            'as' => 'argon.agent.dealstat',
            'uses' => 'UsersController@agent_deal_stat',
        ]);

        Route::get('/common/balance', [
            'as' => 'argon.common.balance',
            'uses' => 'UsersController@balance',
        ]);

        Route::post('/common/balance', [
            'as' => 'argon.common.balance.post',
            'uses' => 'UsersController@updateBalance',
        ]);

        Route::get('/user/profile', [
            'as' => 'argon.common.profile',
            'uses' => 'UsersController@profile',
        ]);

    });
});

?>