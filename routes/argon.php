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


Route::prefix('{slug}')->middleware(['argonbackend', 'auth', 'argonaccessrule'])->group(function () {
	Route::namespace('Backend\Argon')->group(function () {
        Route::get('logout', [
            'as' => 'argon.auth.logout',
            'uses' => 'AuthController@getLogout'
        ]);

        Route::get('/', [
            'as' => 'argon.dashboard',
            'uses' => 'DashboardController@index',
        ]);

        Route::get('/joiners/list', [
            'as' => 'argon.joiners.list',
            'uses' => 'UsersController@joiners_list',
        ]);

        //sharebet route
        Route::get('/share/list', [
            'as' => 'argon.share.index',
            'uses' => 'ShareBetController@index',
        ]);
        Route::get('/share/setting', [
            'as' => 'argon.share.setting',
            'uses' => 'ShareBetController@setting',
        ]);
        Route::post('/share/setting', [
            'as' => 'argon.share.setting.post',
            'uses' => 'ShareBetController@setting_store',
        ]);

        Route::get('/share/rolling/convert', [
            'as' => 'argon.share.rolling.convert',
            'uses' => 'ShareBetController@convert_deal',
        ]);

        Route::get('/share/gamestat', [
            'as' => 'argon.share.gamestat',
            'uses' => 'ShareBetController@gamestat',
        ]);
        Route::get('/share/report/daily', [
            'as' => 'argon.share.report.daily',
            'uses' => 'ShareBetController@report_daily',
        ]);
        Route::get('/share/report/childdaily', [
            'as' => 'argon.share.report.childdaily',
            'uses' => 'ShareBetController@report_childdaily',
        ]);
        Route::get('/share/report/game', [
            'as' => 'argon.share.report.game',
            'uses' => 'ShareBetController@report_game',
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

        Route::get('/agent/joinlist', [
            'as' => 'argon.agent.joinlist',
            'uses' => 'UsersController@agent_joinlist',
        ]);

        Route::get('/agent/move', [
            'as' => 'argon.agent.move',
            'uses' => 'UsersController@agent_move',
        ]);

        Route::post('/agent/move', [
            'as' => 'argon.agent.move.post',
            'uses' => 'UsersController@agent_update',
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

        Route::get('/player/vlist', [
            'as' => 'argon.player.vlist',
            'uses' => 'UsersController@vplayer_list',
        ]);

        Route::delete('/player/terminate', [
            'as' => 'argon.player.terminate',
            'uses' => 'UsersController@player_terminate',
        ])->middleware('simultaneous:1');
        Route::get('/player/logout', [
            'as' => 'argon.player.logout',
            'uses' => 'UsersController@player_logout',
        ])->middleware('simultaneous:1');

        Route::get('/player/processjoin', [
            'as' => 'argon.player.join',
            'uses' => 'UsersController@player_join',
        ]);

        Route::get('/player/joinlist', [
            'as' => 'argon.player.joinlist',
            'uses' => 'UsersController@player_joinlist',
        ]);

        Route::get('/player/refresh', [
            'as' => 'argon.player.refresh',
            'uses' => 'UsersController@player_refresh',
        ]);


        Route::get('/player/transaction', [
            'as' => 'argon.player.transaction',
            'uses' => 'UsersController@player_transaction',
        ]);

        Route::get('/player/gamehistory', [
            'as' => 'argon.player.gamehistory',
            'uses' => 'UsersController@player_game_stat',
        ]);

        Route::get('/player/gamepending', [
            'as' => 'argon.player.gamepending',
            'uses' => 'UsersController@player_game_pending',
        ]);

        Route::get('/player/cancelgame', [
            'as' => 'argon.player.cancelgame',
            'uses' => 'UsersController@player_game_cancel',
        ]);

        Route::get('/player/processgame', [
            'as' => 'argon.player.processgame',
            'uses' => 'UsersController@player_game_process',
        ]);

        

        

        Route::get('/player/gamedetail', [
            'as' => 'argon.player.gamedetail',
            'uses' => 'UsersController@player_game_detail',
        ]);
        
        /// Common


        Route::get('/common/balance', [
            'as' => 'argon.common.balance',
            'uses' => 'CommonController@balance',
        ]);

        Route::post('/common/balance', [
            'as' => 'argon.common.balance.post',
            'uses' => 'CommonController@updateBalance',
        ])->middleware('simultaneous:1');

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
        Route::post('/common/profile/accessrule', [
            'as' => 'argon.common.profile.accessrule',
            'uses' => 'CommonController@updateAccessrule',
        ]);
        Route::post('/common/profile/dwpass', [
            'as' => 'argon.common.profile.dwpass',
            'uses' => 'CommonController@updateDWPass',
        ]);
        Route::get('/common/profile/resetdwpass', [
            'as' => 'argon.common.profile.resetdwpass',
            'uses' => 'CommonController@resetDWPass',
        ]);

        Route::delete('/common/profile/delete', [
            'as' => 'argon.common.profile.delete',
            'uses' => 'CommonController@deleteUser',
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

        Route::get('/dw/process', [
            'as' => 'argon.dw.process',
            'uses' => 'DWController@process',
        ]);
        Route::delete('/dw/reject', [
            'as' => 'argon.dw.reject',
            'uses' => 'DWController@rejectDW',
        ]);
        Route::post('/dw/process', [
            'as' => 'argon.dw.process.post',
            'uses' => 'DWController@processDW',
        ]);

        Route::get('/dw/addmanage', [
            'as' => 'argon.dw.addmanage',
            'uses' => 'DWController@addmanage',
        ]);
        

        Route::get('/dw/outmanage', [
            'as' => 'argon.dw.outmanage',
            'uses' => 'DWController@outmanage',
        ]);


        // added by pine
        /**
         * report routes
         */
        Route::get('/report/daily', [
            'as' => 'argon.report.daily',
            'uses' => 'ReportController@report_daily',
        ]);
        Route::get('/report/childdaily', [
            'as' => 'argon.report.childdaily',
            'uses' => 'ReportController@report_childdaily',
        ]);
        Route::post('/report/dailydw', [
            'as' => 'argon.report.daily.dw.post',
            'uses' => 'ReportController@update_dailydw',
        ]);
        Route::get('/report/dailydw', [
            'as' => 'argon.report.daily.dw',
            'uses' => 'ReportController@report_dailydw',
        ]);
        Route::get('/report/childdaily/{daily_type}', [
            'as' => 'argon.report.childdaily.dw',
            'uses' => 'ReportController@report_childdaily',
        ]);
        Route::get('/report/childmonthly', [
            'as' => 'argon.report.childmonthly',
            'uses' => 'ReportController@report_childmonthly',
        ]);
        Route::get('/report/monthly', [
            'as' => 'argon.report.monthly',
            'uses' => 'ReportController@report_monthly',
        ]);
        Route::post('/report/game', [
            'as' => 'argon.report.game.post',
            'uses' => 'ReportController@update_game',
        ]);
    
        Route::get('/report/game', [
            'as' => 'argon.report.game',
            'uses' => 'ReportController@report_game',
        ]);

        Route::get('/report/gamedetails', [
            'as' => 'argon.report.game.details',
            'uses' => 'ReportController@report_game_details',
        ]);

        /**
         * notices routes
         */

        Route::get('notices', [
            'as' => 'argon.notice.list',
            'uses' => 'NoticesController@index',
        ]);
        Route::get('notices/create', [
            'as' => 'argon.notice.create',
            'uses' => 'NoticesController@create',
        ]);
        Route::post('notices/create', [
            'as' => 'argon.notice.store',
            'uses' => 'NoticesController@store',
        ]);
        Route::get('notices/{notice}/edit', [
            'as' => 'argon.notice.edit',
            'uses' => 'NoticesController@edit',
        ]);
        Route::post('notices/{notice}/update', [
            'as' => 'argon.notice.update',
            'uses' => 'NoticesController@update',
        ]);
        Route::delete('notices/{notice}/delete', [
            'as' => 'argon.notice.delete',
            'uses' => 'NoticesController@delete',
        ]);


        /**
         * template routes
         */

        Route::get('msgtemp', [
            'as' => 'argon.msgtemp.list',
            'uses' => 'MsgTempController@index',
        ]);
        Route::get('msgtemp/create', [
            'as' => 'argon.msgtemp.create',
            'uses' => 'MsgTempController@create',
        ]);
        Route::post('msgtemp/create', [
            'as' => 'argon.msgtemp.store',
            'uses' => 'MsgTempController@store',
        ]);
        Route::get('msgtemp/{msgtemp}/edit', [
            'as' => 'argon.msgtemp.edit',
            'uses' => 'MsgTempController@edit',
        ]);
        Route::post('msgtemp/{msgtemp}/update', [
            'as' => 'argon.msgtemp.update',
            'uses' => 'MsgTempController@update',
        ]);
        Route::delete('msgtemp/{msgtemp}/delete', [
            'as' => 'argon.msgtemp.delete',
            'uses' => 'MsgTempController@delete',
        ]);


        /**
         * messages routes
         */

        Route::get('/messages', [
            'as' => 'argon.msg.list',
            'uses' => 'MessageController@index',
        ]);
        Route::get('messages/create', [
            'as' => 'argon.msg.create',
            'uses' => 'MessageController@create',
        ]);
        Route::post('messages/monitor', [
            'as' => 'argon.msg.monitor',
            'uses' => 'MessageController@updatemonitor',
        ]);
        Route::post('messages/create', [
            'as' => 'argon.msg.store',
            'uses' => 'MessageController@store',
        ]);
        Route::delete('messages/{message}/delete', [
            'as' => 'argon.msg.delete',
            'uses' => 'MessageController@delete',
        ]);
        Route::delete('messages/deleteall', [
            'as' => 'argon.msg.deleteall',
            'uses' => 'MessageController@deleteall',
        ]);
        /**
         * websites routes
         */

        Route::get('websites', [
            'as' => 'argon.website.list',
            'uses' => 'SettingsController@index',
        ]);
        Route::get('websites/status', [
            'as' => 'argon.website.status',
            'uses' => 'SettingsController@status_update',
        ]);
        Route::get('websites/create', [
            'as' => 'argon.website.create',
            'uses' => 'SettingsController@create',
        ]);
        Route::post('websites/create', [
            'as' => 'argon.website.store',
            'uses' => 'SettingsController@store',
        ]);
        Route::get('websites/{website}/edit', [
            'as' => 'argon.website.edit',
            'uses' => 'SettingsController@edit',
        ]);
        Route::post('websites/{website}/update', [
            'as' => 'argon.website.update',
            'uses' => 'SettingsController@update',
        ]);
        Route::delete('websites/{website}/delete', [
            'as' => 'argon.website.delete',
            'uses' => 'SettingsController@delete',
        ]);

        /**
         * Activity Log
         */

        Route::get('activity', [
            'as' => 'argon.activity.index',
            'uses' => 'SettingsController@activity_index',
            'middleware' => 'permission:users.activity',
        ]);
        Route::get('activity/user/{user}/log', [
            'as' => 'argon.activity.user',
            'uses' => 'SettingsController@userActivity'
        ]);

        Route::get('activity/clear', [
            'as' => 'argon.activity.clear',
            'uses' => 'SettingsController@activity_clear',
        ]);

        /**
         * System Statistic
         */

        Route::get('system/statistics', [
            'as' => 'argon.system.statistics',
            'uses' => 'SettingsController@system_values',
        ]);

        Route::get('system/xmxwithdrawall', [
            'as' => 'argon.system.xmxwithdraw',
            'uses' => 'SettingsController@xmx_withdrawall',
        ]);

        Route::get('system/logreset', [
            'as' => 'argon.system.logreset',
            'uses' => 'SettingsController@logreset',
        ]);

        /**
         * Game routes
         */
        Route::get('game/category', [
            'as' => 'argon.game.category',
            'uses' => 'GameController@game_category',
        ]);

        Route::get('game/category/status', [
            'as' => 'argon.game.category.status',
            'uses' => 'GameController@category_update',
        ]);

        Route::get('game/domain', [
            'as' => 'argon.game.domain',
            'uses' => 'GameController@domain_category',
        ]);

        Route::get('game/domain/status', [
            'as' => 'argon.game.domain.status',
            'uses' => 'GameController@domain_update',
        ]);

        Route::get('game/domain/provider', [
            'as' => 'argon.game.domain.provider',
            'uses' => 'GameController@domain_provider_update',
        ]);

        Route::get('game/game', [
            'as' => 'argon.game.game',
            'uses' => 'GameController@game_game',
        ]);

        Route::get('game/game/status', [
            'as' => 'argon.game.game.status',
            'uses' => 'GameController@game_update',
        ]);


        Route::get('game/transaction', [
            'as' => 'argon.game.transaction',
            'uses' => 'GameController@game_transaction',
        ]);
        Route::get('game/bank', [
            'as' => 'argon.game.bank',
            'uses' => 'GameController@game_bank',
        ]);
        Route::get('game/bankbalance', [
            'as' => 'argon.game.bankbalance',
            'uses' => 'GameController@game_bankbalance',
        ]);
        Route::post('game/bankbalance', [
            'as' => 'argon.game.bankbalance.post',
            'uses' => 'GameController@game_bankstore',
        ]);
        Route::get('game/bonusbank', [
            'as' => 'argon.game.bonusbank',
            'uses' => 'GameController@game_bonusbank',
        ]);
        Route::post('game/banksetting', [
            'as' => 'argon.game.banksetting',
            'uses' => 'GameController@gamebanks_setting',
        ]);

        Route::get('game/betlimit', [
            'as' => 'argon.game.betlimit',
            'uses' => 'GameController@game_betlimit',
        ]);
        Route::post('game/betlimit', [
            'as' => 'argon.game.betlimitupdate',
            'uses' => 'GameController@game_betlimitupdate',
        ]);
        Route::get('game/gactable', [
            'as' => 'argon.game.gactable',
            'uses' => 'GameController@game_gactable',
        ]);

        Route::get('game/gactable/update', [
            'as' => 'argon.game.gactable.update',
            'uses' => 'GameController@game_gactableupdate',
        ]);

        Route::get('game/missrole', [
            'as' => 'argon.game.missrole',
            'uses' => 'GameController@game_missrole',
        ]);
        Route::post('game/missrole', [
            'as' => 'argon.game.missroleupdate',
            'uses' => 'GameController@game_missroleupdate',
        ]);

        Route::get('game/missrole/status', [
            'as' => 'argon.game.missrolestatus',
            'uses' => 'GameController@game_missrolestatus',
        ]);
        /**
         * Happyhours routes
         */

        Route::get('happyhours', [
            'as' => 'argon.happyhour.list',
            'uses' => 'HappyHourController@index',
            'middleware' => 'permission:happyhours.manage'
        ]);
        Route::get('happyhours/create', [
            'as' => 'argon.happyhour.create',
            'uses' => 'HappyHourController@create',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::post('happyhours/create', [
            'as' => 'argon.happyhour.store',
            'uses' => 'HappyHourController@store',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::get('happyhours/edit', [
            'as' => 'argon.happyhour.edit',
            'uses' => 'HappyHourController@edit',
        ]);
        Route::post('happyhours/update', [
            'as' => 'argon.happyhour.update',
            'uses' => 'HappyHourController@update',
        ]);
        Route::delete('happyhours/delete', [
            'as' => 'argon.happyhour.delete',
            'uses' => 'HappyHourController@delete',
            'middleware' => 'permission:happyhours.delete'
        ]);
    });
});

?>