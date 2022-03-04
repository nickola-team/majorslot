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

        //agent

        Route::get('/agent/create', [
            'as' => 'argon.agent.create',
            'uses' => 'UsersController@agent_create',
        ]);

        Route::post('/agent/create', [
            'as' => 'argon.agent.store',
            'uses' => 'UsersController@agent_store',
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

        //player

        Route::get('/player/create', [
            'as' => 'argon.player.create',
            'uses' => 'UsersController@player_create',
        ]);

        Route::post('/player/create', [
            'as' => 'argon.player.store',
            'uses' => 'UsersController@player_store',
        ]);

        Route::get('/player/list', [
            'as' => 'argon.player.list',
            'uses' => 'UsersController@player_list',
        ]);


        Route::get('/player/transaction', [
            'as' => 'argon.player.transaction',
            'uses' => 'UsersController@player_transaction',
        ]);

        Route::get('/player/gamehistory', [
            'as' => 'argon.player.gamehistory',
            'uses' => 'UsersController@player_game_stat',
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
        Route::get('/common/profile/resetdwpass', [
            'as' => 'argon.common.profile.resetdwpass',
            'uses' => 'CommonController@resetDWPass',
        ]);

        /// deposit & withdraw
        Route::get('/dw/addrequest', [
            'as' => 'argon.dw.addrequest',
            'uses' => 'DWController@addrequest',
        ]);

        Route::get('/dw/outrequest', [
            'as' => 'argon.dw.outrequest',
            'uses' => 'DWController@outrequest',
        ]);

        Route::get('/dw/rolling', [
            'as' => 'argon.dw.dealconvert',
            'uses' => 'DWController@dealconvert',
        ]);

        Route::get('/dw/history', [
            'as' => 'argon.dw.history',
            'uses' => 'DWController@history',
        ]);

        Route::get('/dw/addmanage', [
            'as' => 'argon.dw.addmanage',
            'uses' => 'DWController@addmanage',
        ]);

        Route::get('/dw/outmanage', [
            'as' => 'argon.dw.outmanage',
            'uses' => 'DWController@outmanage',
        ]);

    });
});

?>