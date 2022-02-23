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
            'uses' => 'AuthController@getLogout'
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
        
        /// Common


        Route::get('/common/balance', [
            'as' => 'argon.common.balance',
            'uses' => 'CommonController@balance',
        ]);

        Route::post('/common/balance', [
            'as' => 'argon.common.balance.post',
            'uses' => 'CommonController@updateBalance',
        ]);

        Route::get('/common/profile', [
            'as' => 'argon.common.profile',
            'uses' => 'CommonController@profile',
        ]);
        Route::post('/common/profile/update', [
            'as' => 'argon.common.profile.detail',
            'uses' => 'CommonController@updateProfile',
        ]);
        Route::post('/common/profile/password', [
            'as' => 'argon.common.profile.password',
            'uses' => 'CommonController@updatePassword',
        ]);
        Route::post('/common/profile/dwpass', [
            'as' => 'argon.common.profile.dwpass',
            'uses' => 'CommonController@updateDWPass',
        ]);
        Route::post('/common/profile/resetdwpass', [
            'as' => 'argon.common.profile.resetdwpass',
            'uses' => 'CommonController@resetDWPass',
        ]);

    });
});

?>