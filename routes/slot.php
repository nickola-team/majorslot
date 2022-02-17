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

Route::prefix('slot')->group(function () {
    Route::namespace('Backend')->group(function () {
        Route::get('login', [
            'as' => 'slot.auth.login',
            'uses' => 'Auth\AuthController@getLogin'
        ]);
        Route::post('login', [
            'as' => 'slot.auth.login.post',
            'uses' => 'Auth\AuthController@postLogin'
        ]);
    });
});

Route::prefix('slot')->middleware(['auth'])->group(function () {
	Route::namespace('Backend')->group(function () {


	Route::get('logout', [
		'as' => 'slot.auth.logout',
		'uses' => 'Auth\AuthController@getLogout'
	]);


	
	
    /**
     * Dashboard
     */


    Route::get('/search', [
        'as' => 'slot.search',
        'uses' => 'DashboardController@search',
        'middleware' => 'permission:full.search',
    ]);

    Route::get('/', [
        'as' => 'slot.dashboard',
        'uses' => 'DashboardController@index',
        //
    ]);
	Route::get('/stat_game', [
        'as' => 'slot.game_stat',
        'uses' => 'DashboardController@game_stat',
        'middleware' => 'permission:stats.game',
    ]);
    
	Route::delete('/game_stat/clear', [
        'as' => 'slot.game_stat.clear',
        'uses' => 'DashboardController@game_stat_clear'
    ]);
	Route::get('/bank_stat', [
        'as' => 'slot.bank_stat',
        'uses' => 'DashboardController@bank_stat',
        'middleware' => 'permission:stats.bank',
    ]);
	Route::get('/shop_stat', [
        'as' => 'slot.shop_stat',
        'uses' => 'DashboardController@shop_stat',
        'middleware' => 'permission:stats.shop',
    ]);
	Route::get('/shift_stat', [
        'as' => 'slot.shift_stat',
        'uses' => 'DashboardController@shift_stat',
        'middleware' => 'permission:stats.shift',
    ]);
	Route::get('/live', [
        'as' => 'slot.live_stat',
        'uses' => 'DashboardController@live_stat',
        'middleware' => 'permission:stats.live',
    ]);
    Route::get('/deal_stat', [
        'as' => 'slot.deal_stat',
        'uses' => 'DashboardController@deal_stat',
        'middleware' => 'permission:stats.shop',
    ]);
    Route::delete('/deal_stat/clear', [
        'as' => 'slot.deal_stat.clear',
        'uses' => 'DashboardController@deal_stat_clear'
    ]);
	
	
	Route::get('/start_shift', [
        'as' => 'slot.start_shift',
        'uses' => 'DashboardController@start_shift'
    ]);

    //added by shev 2021-02-15
    Route::get('/adjustment_partner', [
        'as' => 'slot.adjustment_partner',
        'uses' => 'DashboardController@adjustment_partner',
        'middleware' => 'permission:stats.game',
    ]);
    Route::get('/adjustment_ggr', [
        'as' => 'slot.adjustment_ggr',
        'uses' => 'DashboardController@adjustment_ggr',
        'middleware' => 'permission:stats.game',
    ]);
    Route::get('/process_ggr', [
        'as' => 'slot.process_ggr',
        'uses' => 'DashboardController@process_ggr',
        'middleware' => 'permission:stats.game',
    ]);
    Route::get('/reset_ggr', [
        'as' => 'slot.reset_ggr',
        'uses' => 'DashboardController@reset_ggr',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_daily', [
        'as' => 'slot.adjustment_daily',
        'uses' => 'DashboardController@adjustment_daily',
    ]);

    Route::get('/adjustment_monthly', [
        'as' => 'slot.adjustment_monthly',
        'uses' => 'DashboardController@adjustment_monthly',
    ]);

    Route::get('/adjustment_game', [
        'as' => 'slot.adjustment_game',
        'uses' => 'DashboardController@adjustment_game',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_month', [
        'as' => 'slot.adjustment_month',
        'uses' => 'DashboardController@adjustment_month',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_shift', [
        'as' => 'slot.adjustment_shift',
        'uses' => 'DashboardController@adjustment_shift',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_create_shift', [
        'as' => 'slot.adjustment_create_shift',
        'uses' => 'DashboardController@adjustment_create_shift',
        'middleware' => 'permission:stats.game',
    ]);    

    Route::get('/adjustment_shift_stat', [
        'as' => 'slot.adjustment_shift_stat',
        'uses' => 'DashboardController@adjustment_shift_stat'
    ]);

    Route::get('/in_out_request', [
        'as' => 'slot.in_out_request',
        'uses' => 'DashboardController@in_out_request'
    ]);
    Route::get('/in_out_history', [
        'as' => 'slot.in_out_history',
        'uses' => 'DashboardController@in_out_history'
    ]);    

    Route::get('/in_out_manage/{type}', [
        'as' => 'slot.in_out_manage',
        'uses' => 'DashboardController@in_out_manage'
    ]);

    Route::get('/bonus/pplist', [
        'as' => 'slot.bonus.pp',
        'uses' => 'BonusController@pp_index'
    ]);
    Route::get('/bonus/ppadd', [
        'as' => 'slot.bonus.ppadd',
        'uses' => 'BonusController@pp_add'
    ]);
    Route::post('/bonus/ppstore', [
        'as' => 'slot.bonus.ppstore',
        'uses' => 'BonusController@pp_store'
    ]);
    Route::delete('/bonus/ppcancel/{bonusCode}', [
        'as' => 'slot.bonus.ppcancel',
        'uses' => 'BonusController@pp_cancel'
    ]);
    Route::get('/bonus/bnglist', [
        'as' => 'slot.bonus.bng',
        'uses' => 'BonusController@bng_index'
    ]);
    Route::get('/bonus/bngadd', [
        'as' => 'slot.bonus.bngadd',
        'uses' => 'BonusController@bng_add'
    ]);
    Route::post('/bonus/bngstore', [
        'as' => 'slot.bonus.bngstore',
        'uses' => 'BonusController@bng_store'
    ]);
    Route::delete('/bonus/bngcancel/{bonusCode}', [
        'as' => 'slot.bonus.bngcancel',
        'uses' => 'BonusController@bng_cancel'
    ]);

   
	
    /**
     * User Profile
     */

    Route::get('profile', [
        'as' => 'slot.profile',
        'uses' => 'ProfileController@index'
    ]);
    Route::get('profile/activity', [
        'as' => 'slot.profile.activity',
        'uses' => 'ProfileController@activity'
    ]);
    Route::put('profile/details/update', [
        'as' => 'slot.profile.update.details',
        'uses' => 'ProfileController@updateDetails'
    ]);
    Route::post('profile/avatar/update', [
        'as' => 'slot.profile.update.avatar',
        'uses' => 'ProfileController@updateAvatar'
    ]);
    Route::post('profile/avatar/update/external', [
        'as' => 'slot.profile.update.avatar-external',
        'uses' => 'ProfileController@updateAvatarExternal'
    ]);
    Route::put('profile/login-details/update', [
        'as' => 'slot.profile.update.login-details',
        'uses' => 'ProfileController@updateLoginDetails'
    ]);
    Route::post('profile/two-factor/enable', [
        'as' => 'slot.profile.two-factor.enable',
        'uses' => 'ProfileController@enableTwoFactorAuth'
    ]);
    Route::post('profile/two-factor/disable', [
        'as' => 'slot.profile.two-factor.disable',
        'uses' => 'ProfileController@disableTwoFactorAuth'
    ]);
    Route::get('profile/sessions', [
        'as' => 'slot.profile.sessions',
        'uses' => 'ProfileController@sessions'
    ]);
    Route::delete('profile/sessions/{session}/invalidate', [
        'as' => 'slot.profile.sessions.invalidate',
        'uses' => 'ProfileController@invalidateSession'
    ]);
	Route::match(['get','post'], 'profile/setshop', [
        'as' => 'slot.profile.setshop',
        'uses' => 'ProfileController@setshop'
    ]);

    /**
     * User Management
    */
	
    Route::get('user', [
        'as' => 'slot.user.list',
        'uses' => 'UsersController@index',
        'middleware' => 'permission:users.manage'
    ]);
    Route::get('join', [
        'as' => 'slot.user.join',
        'uses' => 'UsersController@join',
        'middleware' => 'permission:users.manage'
    ]);
    Route::post('processjoin', [
        'as' => 'slot.user.processjoin',
        'uses' => 'UsersController@processJoin',
    ]);
    Route::get('tree', [
        'as' => 'slot.user.tree',
        'uses' => 'UsersController@tree',
        'middleware' => 'permission:users.tree'
    ]);
    Route::get('black', [
        'as' => 'slot.black.list',
        'uses' => 'UsersController@blacklist',
    ]);
    Route::get('black/create', [
        'as' => 'slot.black.create',
        'uses' => 'UsersController@blackcreate',
    ]);
    Route::post('black/create', [
        'as' => 'slot.black.store',
        'uses' => 'UsersController@blackstore',
    ]);

    Route::get('black/{blackid}/edit', [
        'as' => 'slot.black.edit',
        'uses' => 'UsersController@blackedit',
    ]);  
    Route::post('black/{blackid}/update', [
        'as' => 'slot.black.update',
        'uses' => 'UsersController@blackupdate',
    ]);
    Route::delete('black/{blackid}/remove', [
        'as' => 'slot.black.remove',
        'uses' => 'UsersController@blackremove',
    ]);


    Route::get('partner/{role_id}', [
        'as' => 'slot.user.partner',
        'uses' => 'UsersController@partner',
    ]);
    Route::get('statistics', [
        'as' => 'slot.statistics',
        'uses' => 'DashboardController@statistics',
        'middleware' => 'permission:stats.pay',
    ]);	
    Route::get('partner_statistics', [
        'as' => 'slot.statistics_partner',
        'uses' => 'DashboardController@statistics_partner',
        'middleware' => 'permission:stats.pay',
    ]);
    Route::get('mileage_stat', [
        'as' => 'slot.mileage_stat',
        'uses' => 'DashboardController@mileage_stat',
        'middleware' => 'permission:stats.pay',
    ]);	
    Route::post('partner/master/reset_ggr', [
        'uses' => 'UsersController@reset_ggr',
		'as' => 'slot.user.partner.reset_ggr',
    ]);
	Route::post('profile/balance/update', [
        'uses' => 'UsersController@updateBalance',
		'as' => 'slot.user.balance.update',
		'middleware' => 'permission:users.balance.manage'
    ])->middleware('simultaneous:1');
    Route::get('profile/balance/setbonus/{userid}', [
        'uses' => 'UsersController@setBonusSetting',
		'as' => 'slot.user.balance.bonus',
		'middleware' => 'permission:users.balance.manage'
    ]);
    Route::get('user/create', [
        'as' => 'slot.user.create',
        'uses' => 'UsersController@create',
        'middleware' => 'permission:users.add'
    ]);
    Route::get('user/createuserfromcsv', [
        'as' => 'slot.user.createuserfromcsv',
        'uses' => 'UsersController@createuserfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/createuserfromcsv', [
        'as' => 'slot.user.storeuserfromcsv',
        'uses' => 'UsersController@storeuserfromcsv',
        'middleware' => 'permission:users.add'
    ]);    
    Route::get('user/createpartnerfromcsv', [
        'as' => 'slot.user.createpartnerfromcsv',
        'uses' => 'UsersController@createpartnerfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/createpartnerfromcsv', [
        'as' => 'slot.user.storepartnerfromcsv',
        'uses' => 'UsersController@storepartnerfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/create', [
        'as' => 'slot.user.store',
        'uses' => 'UsersController@store',
        'middleware' => 'permission:users.add'
    ]);

	Route::get('user/{user}/stat', [
        'as' => 'slot.user.stat',
        'uses' => 'UsersController@statistics'
    ]);
    Route::post('user/mass', [
        'as' => 'slot.user.massadd',
        'uses' => 'UsersController@massadd',
        'middleware' => 'permission:users.add'
    ]);
    Route::get('user/{user}/show', [
        'as' => 'slot.user.show',
        'uses' => 'UsersController@view'
    ]);
    Route::get('user/{user}/profile', [
        'as' => 'slot.user.edit',
        'uses' => 'UsersController@edit'
    ]);
    Route::put('user/{user}/update/details', [
        'as' => 'slot.user.update.details',
        'uses' => 'UsersController@updateDetails'
    ]);
    Route::get('user/{user}/update/reset_confirm_pwd', [
        'as' => 'slot.user.update.resetpwd',
        'uses' => 'UsersController@resetConfirmPwd'
    ]);  
    Route::post('user/{user}/update/move', [
        'as' => 'slot.user.update.move',
        'uses' => 'UsersController@move'
    ]);
    Route::put('user/{user}/update/login-details', [
        'as' => 'slot.user.update.login-details',
        'uses' => 'UsersController@updateLoginDetails'
    ]);
    Route::delete('user/{user}/delete', [
        'as' => 'slot.user.delete',
        'uses' => 'UsersController@delete',
		'middleware' => 'permission:users.delete'
    ]);
    Route::delete('user/{user}/hard_delete', [
        'as' => 'slot.user.hard_delete',
        'uses' => 'UsersController@hard_delete',
        'middleware' => 'permission:users.delete'
    ]);
    Route::post('user/{user}/update/avatar', [
        'as' => 'slot.user.update.avatar',
        'uses' => 'UsersController@updateAvatar'
    ]);
    Route::post('user/{user}/update/address', [
        'as' => 'slot.user.update.address',
        'uses' => 'UsersController@updateAddress'
    ]);
    Route::post('user/{user}/update/avatar/external', [
        'as' => 'slot.user.update.avatar.external',
        'uses' => 'UsersController@updateAvatarExternal'
    ]);
    Route::get('user/{user}/sessions', [
        'as' => 'slot.user.sessions',
        'uses' => 'UsersController@sessions'
    ]);
    Route::delete('user/{user}/sessions/{session}/invalidate', [
        'as' => 'slot.user.sessions.invalidate',
        'uses' => 'UsersController@invalidateSession'
    ]);
    Route::post('user/{user}/two-factor/enable', [
        'as' => 'slot.user.two-factor.enable',
        'uses' => 'UsersController@enableTwoFactorAuth'
    ]);
    Route::post('user/{user}/two-factor/disable', [
        'as' => 'slot.user.two-factor.disable',
        'uses' => 'UsersController@disableTwoFactorAuth'
    ]);

        Route::delete('user/action/{action}', [
            'as' => 'slot.user.action',
            'uses' => 'UsersController@action',
        ]);
	
	/**
     * Games routes
    */

	Route::get('game', [
        'as' => 'slot.game.list',
        'uses' => 'GamesController@index',
        'middleware' => 'permission:games.manage'
    ]);	
    Route::get('gamebank', [
        'as' => 'slot.game.bank',
        'uses' => 'GamesController@bank',
        'middleware' => 'permission:games.manage'
    ]);	
    Route::get('bonusbank', [
        'as' => 'slot.game.bonusbank',
        'uses' => 'GamesController@bonusbank',
        'middleware' => 'permission:games.manage'
    ]);	
	Route::get('games.json', [
        'as' => 'slot.game.list.json',
        'uses' => 'GamesController@index_json'
    ]);
    Route::get('game/create', [
        'as' => 'slot.game.create',		
        'uses' => 'GamesController@create',
        'middleware' => 'permission:games.add'
    ]);
    Route::post('game/create', [
        'as' => 'slot.game.store',		
        'uses' => 'GamesController@store',
        'middleware' => 'permission:games.add'
    ]);
    Route::get('game/{game}/show', [
        'as' => 'slot.game.show',		
        'uses' => 'GamesController@view',
    ]);	
	Route::get('game/{game}', [
        'as' => 'slot.game.go',
        'uses' => 'GamesController@go'
    ]);	
	Route::post('/game/{game}/server', [
        'as' => 'slot.game.server',
        'uses' => 'GamesController@server'
    ]);
    Route::get('game/{game}/edit', [
        'as' => 'slot.game.edit',		
        'uses' => 'GamesController@edit',
        'middleware' => 'permission:games.edit'
    ]);	
	Route::post('game/{game}/update', [
        'as' => 'slot.game.update',		
        'uses' => 'GamesController@update',
    ]);	
	Route::delete('game/{game}/delete', [
        'as' => 'slot.game.delete',		
        'uses' => 'GamesController@delete',
        'middleware' => 'permission:games.delete'
    ]);	
	Route::post('game/categories', [
        'as' => 'slot.game.categories',		
        'uses' => 'GamesController@categories',
    ]);
    Route::post('game/update/mass', [
        'as' => 'slot.game.mass',
        'uses' => 'GamesController@mass',
        'middleware' => 'permission:games.edit'
    ]);


    Route::post('gamebanks_add', [
       'as' => 'slot.game.gamebanks_add',
        'uses' => 'GamesController@gamebanks_add',
    ]);
    Route::post('gamebanks_setting', [
        'as' => 'slot.game.gamebanks_setting',
         'uses' => 'GamesController@gamebanks_setting',
     ]); 
    Route::get('gamebanks_clear', [
        'as' => 'slot.game.gamebanks_clear',
        'uses' => 'GamesController@gamebanks_clear',
    ]);



        /**
     * Categories routes
     */

	Route::get('category', [
        'as' => 'slot.category.list',
        'uses' => 'CategoriesController@index',
        'middleware' => 'permission:categories.manage'
    ]);	
    Route::get('category/create', [
        'as' => 'slot.category.create',		
        'uses' => 'CategoriesController@create',
        'middleware' => 'permission:categories.add'
    ]);
    Route::post('category/create', [
        'as' => 'slot.category.store',		
        'uses' => 'CategoriesController@store',
        'middleware' => 'permission:categories.add'
    ]);    
    Route::get('category/{category}/edit', [
        'as' => 'slot.category.edit',		
        'uses' => 'CategoriesController@edit',
    ]);	
	Route::post('category/{category}/update', [
        'as' => 'slot.category.update',		
        'uses' => 'CategoriesController@update',
    ]);	
	Route::delete('category/{category}/delete', [
        'as' => 'slot.category.delete',		
        'uses' => 'CategoriesController@delete',
        'middleware' => 'permission:categories.delete'
    ]);
    Route::get('category/{category}/show', [
        'as' => 'slot.category.show',		
        'uses' => 'CategoriesController@view',
    ]);	
	/**
     * Categories routes
     */

	Route::get('shops', [
        'as' => 'slot.shop.list',
        'uses' => 'ShopsController@index',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::get('shops/create', [
        'as' => 'slot.shop.create',
        'uses' => 'ShopsController@create',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::post('shops/create', [
        'as' => 'slot.shop.store',
        'uses' => 'ShopsController@store',
        'middleware' => 'permission:shops.manage'
    ]);

    Route::get('shops/admin/create', [
        'as' => 'slot.shop.admin_create',
        'uses' => 'ShopsController@admin_create',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::post('shops/admin/create', [
        'as' => 'slot.shop.admin_store',
        'uses' => 'ShopsController@admin_store',
        'middleware' => 'permission:shops.manage'
    ]);

    Route::get('shops/{shop}/edit', [
        'as' => 'slot.shop.edit',
        'uses' => 'ShopsController@edit',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::post('shops/{shop}/update', [
        'as' => 'slot.shop.update',
        'uses' => 'ShopsController@update',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::post('shops/balance', [
        'as' => 'slot.shop.balance',
        'uses' => 'ShopsController@balance',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::delete('shops/{shop}/delete', [
        'as' => 'slot.shop.delete',
        'uses' => 'ShopsController@delete',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::delete('shops/{shop}/hard_delete', [
        'as' => 'slot.shop.hard_delete',
        'uses' => 'ShopsController@hard_delete',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::delete('shops/{shop}/action/{action}', [
        'as' => 'slot.shop.action',
        'uses' => 'ShopsController@action',
        'middleware' => 'permission:shops.manage'
    ]);


        /**
         * notices routes
         */

        Route::get('notices', [
            'as' => 'slot.notice.list',
            'uses' => 'NoticesController@index',
        ]);
        Route::get('notices/create', [
            'as' => 'slot.notice.create',
            'uses' => 'NoticesController@create',
        ]);
        Route::post('notices/create', [
            'as' => 'slot.notice.store',
            'uses' => 'NoticesController@store',
        ]);
        Route::get('notices/{notice}/edit', [
            'as' => 'slot.notice.edit',
            'uses' => 'NoticesController@edit',
        ]);
        Route::post('notices/{notice}/update', [
            'as' => 'slot.notice.update',
            'uses' => 'NoticesController@update',
        ]);
        Route::delete('notices/{notice}/delete', [
            'as' => 'slot.notice.delete',
            'uses' => 'NoticesController@delete',
        ]);

        /**
     * messages routes
     */

    Route::get('messages', [
        'as' => 'slot.msg.list',
        'uses' => 'MessageController@index',
    ]);
    Route::get('messages/create', [
        'as' => 'slot.msg.create',
        'uses' => 'MessageController@create',
    ]);
    Route::post('messages/create', [
        'as' => 'slot.msg.store',
        'uses' => 'MessageController@store',
    ]);
    Route::delete('messages/{message}/delete', [
        'as' => 'slot.msg.delete',
        'uses' => 'MessageController@delete',
    ]);

        /**
         * websites routes
         */

        Route::get('websites', [
            'as' => 'slot.website.list',
            'uses' => 'WebsitesController@index',
        ]);
        Route::get('websites/create', [
            'as' => 'slot.website.create',
            'uses' => 'WebsitesController@create',
        ]);
        Route::post('websites/create', [
            'as' => 'slot.website.store',
            'uses' => 'WebsitesController@store',
        ]);
        Route::get('websites/{website}/edit', [
            'as' => 'slot.website.edit',
            'uses' => 'WebsitesController@edit',
        ]);
        Route::post('websites/{website}/update', [
            'as' => 'slot.website.update',
            'uses' => 'WebsitesController@update',
        ]);
        Route::delete('websites/{website}/delete', [
            'as' => 'slot.website.delete',
            'uses' => 'WebsitesController@delete',
        ]);

        /**
         * Happyhours routes
         */

        Route::get('happyhours', [
            'as' => 'slot.happyhour.list',
            'uses' => 'HappyHourController@index',
            'middleware' => 'permission:happyhours.manage'
        ]);
        Route::get('happyhours/create', [
            'as' => 'slot.happyhour.create',
            'uses' => 'HappyHourController@create',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::post('happyhours/create', [
            'as' => 'slot.happyhour.store',
            'uses' => 'HappyHourController@store',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::get('happyhours/{happyhour}/edit', [
            'as' => 'slot.happyhour.edit',
            'uses' => 'HappyHourController@edit',
        ]);
        Route::post('happyhours/{happyhour}/update', [
            'as' => 'slot.happyhour.update',
            'uses' => 'HappyHourController@update',
        ]);
        Route::delete('happyhours/{happyhour}/delete', [
            'as' => 'slot.happyhour.delete',
            'uses' => 'HappyHourController@delete',
            'middleware' => 'permission:happyhours.delete'
        ]);

        /**
         * Roles & Permissions
         */

        Route::get('jpgame', [
            'as' => 'slot.jpgame.list',
            'uses' => 'JPGController@index',
            //'middleware' => 'permission:jackpots.manage',
        ]);
        Route::get('jpgame/create', [
            'as' => 'slot.jpgame.create',
            'uses' => 'JPGController@create',
            //'middleware' => 'permission:jackpots.add'
        ]);
        Route::post('jpgame/create', [
            'as' => 'slot.jpgame.store',
            'uses' => 'JPGController@store',
            //'middleware' => 'permission:jackpots.add'
        ]);
        Route::get('jpgame/{jackpot}/edit', [
            'as' => 'slot.jpgame.edit',
            'uses' => 'JPGController@edit',
        ]);
        Route::post('jpgame/{jackpot}/update', [
            'as' => 'slot.jpgame.update',
            'uses' => 'JPGController@update',
        ]);
        Route::post('jpgame/balance', [
            'as' => 'slot.jpgame.balance',
            'uses' => 'JPGController@balance',
        ]);




        /**
     * Roles & Permissions
     */

    Route::get('role', [
        'as' => 'slot.role.index',
        'uses' => 'RolesController@index',
        'middleware' => 'permission:roles.manage'
    ]);
    Route::get('role/create', [
        'as' => 'slot.role.create',
        'uses' => 'RolesController@create'
    ]);
    Route::post('role/store', [
        'as' => 'slot.role.store',
        'uses' => 'RolesController@store'
    ]);
    Route::get('role/{role}/edit', [
        'as' => 'slot.role.edit',
        'uses' => 'RolesController@edit'
    ]);
    Route::put('role/{role}/update', [
        'as' => 'slot.role.update',
        'uses' => 'RolesController@update'
    ]);
    Route::delete('role/{role}/delete', [
        'as' => 'slot.role.delete',
        'uses' => 'RolesController@delete'
    ]);	
	
    Route::post('permission/save', [
        'as' => 'slot.permission.save',
        'uses' => 'PermissionsController@saveRolePermissions'
    ]);
	
	/**
     * Permissions
     */
	 
	Route::get('permission', [
        'as' => 'slot.permission.index',
        'uses' => 'PermissionsController@index',
        'middleware' => 'permission:permissions.manage'
    ]);
    Route::get('permission/create', [
        'as' => 'slot.permission.create',
        'uses' => 'PermissionsController@create',
        'middleware' => 'permission:permissions.add'
    ]);
    Route::post('permission/store', [
        'as' => 'slot.permission.store',
        'uses' => 'PermissionsController@store',
        'middleware' => 'permission:permissions.add'
    ]);
    Route::get('permission/{permission}/edit', [
        'as' => 'slot.permission.edit',
        'uses' => 'PermissionsController@edit'
    ]);
    Route::put('permission/{permission}/update', [
        'as' => 'slot.permission.update',
        'uses' => 'PermissionsController@update'
    ]);
    Route::delete('permission/{permission}/delete', [
        'as' => 'slot.permission.delete',
        'uses' => 'PermissionsController@delete'
    ]);	
	

    /**
     * Settings
     */

    Route::get('settings', [
        'as' => 'slot.settings.general',
        'uses' => 'SettingsController@general',
        'middleware' => 'permission:settings.general',
    ]);
    Route::post('settings/general', [
        'as' => 'slot.settings.general.update',
        'uses' => 'SettingsController@update',
        'middleware' => 'permission:settings.general'
    ]);

    Route::get('settings/auth', [
        'as' => 'slot.settings.auth',
        'uses' => 'SettingsController@auth',
        'middleware' => 'permission:settings.auth'
    ]);
    Route::post('settings/auth', [
        'as' => 'slot.settings.auth.update',
        'uses' => 'SettingsController@update',
        'middleware' => 'permission:settings.auth'
    ]);
	
	Route::get('generator', [
        'as' => 'slot.settings.generator',
        'uses' => 'SettingsController@generator',
        'middleware' => 'permission:settings.generator'
    ]);
	
	Route::post('generator', [
        'as' => 'slot.settings.generator.post',
        'uses' => 'SettingsController@generator',
        'middleware' => 'permission:settings.generator'
    ]);

    Route::put('shops/block', [
        'as' => 'slot.settings.shop_block',
        'uses' => 'SettingsController@shop_block',
        'middleware' => 'permission:shops.block'
    ]);

    Route::put('shops/unblock', [
        'as' => 'slot.settings.shop_unblock',
        'uses' => 'SettingsController@shop_unblock',
        'middleware' => 'permission:shops.unblock'
    ]);

    Route::put('settings/sync', [
        'as' => 'slot.settings.sync',
        'uses' => 'SettingsController@sync'
    ]);
	

    /**
     * Activity Log
     */

    Route::get('activity', [
        'as' => 'slot.activity.index',
        'uses' => 'ActivityController@index',
        'middleware' => 'permission:users.activity',
    ]);
    Route::get('activity/user/{user}/log', [
        'as' => 'slot.activity.user',
        'uses' => 'ActivityController@userActivity'
    ]);

    Route::delete('activity/clear', [
        'as' => 'slot.activity.clear',
        'uses' => 'ActivityController@clear',
    ]);

    //added by shev

    // Route::post('api/generateFreespin', [
    //     'as' => 'slot.api.generateFreespin',
    //     'uses' => 'SpinGeneraterController@generateFreespin',
    // ]);
    // Route::post('api/generateFreespin', [
    //     'as' => 'slot.api.generateFreespin',
    //     'uses' => 'DancingDrumSpinGeneraterController@generateFreespin',
    // ]);

    Route::post('api/generateFreespin', [
        'as' => 'slot.api.generateFreespin',
        'uses' => 'EightFortuneSpinGeneraterController@generateFreespin',
    ]);

	});
});

?>