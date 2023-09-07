<?php


Route::namespace('Frontend')->middleware(['siteisclosed'])->group(function () {
	
	Route::get('login', [
		'as' => 'frontend.auth.login',
		'uses' => 'GamesController@index'
	]);

	Route::get('launcher/{game}/{token}/{mode}','Auth\AuthController@apiLogin' );
	
	
	Route::post('login', [
		'as' => 'frontend.auth.login.post',
		'uses' => 'Auth\AuthController@postLogin'
	]);	
	Route::get('logout', [
		'as' => 'frontend.auth.logout',
		'uses' => 'Auth\AuthController@getLogout'
	]);

    Route::get('join', [
        'as' => 'frontend.auth.join',
        'uses' => 'Auth\AuthController@getJoin'
    ]);   
          
	
	// Allow registration routes only if registration is enabled.
    /*
	if (settings('reg_enabled')) {

		Route::get('register', [
			'as' => 'frontend.register',
			'uses' => 'Auth\AuthController@getRegister'
		]);

		Route::post('register', [
			'as' => 'frontend.register.post',
			'uses' => 'Auth\AuthController@postRegister'
		]);

        Route::get('register/confirmation/{token}', [
            'as' => 'frontend.register.confirm-email',
            'uses' => 'Auth\AuthController@confirmEmail'
        ]);

	}
	*/
/*
    if (settings('forgot_password')) {

        Route::get('password/remind', [
            'as' => 'frontend.password.remind',
            'uses' => 'Auth\PasswordController@forgotPassword'
        ]);
        Route::post('password/remind', [
            'as' => 'frontend.password.remind.post',
            'uses' => 'Auth\PasswordController@sendPasswordReminder'
        ]);
        Route::get('password/reset/{token}', [
            'as' => 'frontend.password.reset',
            'uses' => 'Auth\PasswordController@getReset'
        ]);
        Route::post('password/reset', [
            'as' => 'frontend.password.reset.post',
            'uses' => 'Auth\PasswordController@postReset'
        ]);
    }
    
	
	
	Route::get('new-license', [
        'as' => 'frontend.new_license',
        'uses' => 'PagesController@new_license'
    ]);
	Route::post('new-license', [
        'as' => 'frontend.new_license.post',
        'uses' => 'PagesController@new_license_post'
    ]);
	
	Route::get('license-error', [
        'as' => 'frontend.page.error_license',
        'uses' => 'PagesController@error_license'
    ]);
*/
    Route::get('jpstv/{id?}', [
        'as' => 'frontend.jpstv',
        'uses' => 'PagesController@jpstv'
    ]);

    Route::get('jpstv.json', [
        'as' => 'frontend.jpstv_json',
        'uses' => 'PagesController@jpstv_json'
    ]);
	
	/**
     * Dashboard
     */

    /*
	Route::get('statistics', [
        'as' => 'frontend.statistics',
        'uses' => 'DashboardController@statistics'
    ]);
    */
    Route::get('subsession', [
        'as' => 'frontend.subsession',
        'uses' => 'GamesController@subsession'
    ]);

	/**
     * User Profile
     */

    Route::get('profile', [
        'as' => 'frontend.profile',
        'uses' => 'ProfileController@index'
    ]);
    Route::get('profile/dealout', [
        'as' => 'frontend.profile.dealout',
        'uses' => 'ProfileController@dealout'
    ]);
    Route::get('profile/mypage', [
        'as' => 'frontend.profile.mypage',
        'uses' => 'ProfileController@mypage'
    ]);
 
    // Route::get('profile/activity', [
    //     'as' => 'frontend.profile.activity',
    //     'uses' => 'ProfileController@activity'
    // ]);
	// Route::get('profile/balance', [
    //     'as' => 'frontend.profile.balance',
    //     'uses' => 'ProfileController@balance'
    // ]);
	// Route::post('profile/balance', [
    //     'as' => 'frontend.profile.balance.post',
    //     'uses' => 'ProfileController@balanceAdd'
    // ]);
	// Route::get('profile/balance/success', [
    //     'as' => 'frontend.profile.balance.success',
    //     'uses' => 'ProfileController@success'
    // ]);
	// Route::get('profile/balance/fail', [
    //     'as' => 'frontend.profile.balance.fail',
    //     'uses' => 'ProfileController@fail'
    // ]);
    // Route::post('profile/details/update', [
    //     'as' => 'frontend.profile.update.details',
    //     'uses' => 'ProfileController@updateDetails'
    // ]);
	// Route::post('profile/password/update', [
    //     'as' => 'frontend.profile.update.password',
    //     'uses' => 'ProfileController@updatePassword'
    // ]);
    // Route::post('profile/avatar/update', [
    //     'as' => 'frontend.profile.update.avatar',
    //     'uses' => 'ProfileController@updateAvatar'
    // ]);
    // Route::post('profile/avatar/update/external', [
    //     'as' => 'frontend.profile.update.avatar-external',
    //     'uses' => 'ProfileController@updateAvatarExternal'
    // ]);
	
	// Route::post('profile/exchange', [
    //     'as' => 'frontend.profile.exchange',
    //     'uses' => 'ProfileController@exchange'
    // ]);
	
    // Route::put('profile/login-details/update', [
    //     'as' => 'frontend.profile.update.login-details',
    //     'uses' => 'ProfileController@updateLoginDetails'
    // ]);
    // Route::post('profile/two-factor/enable', [
    //     'as' => 'frontend.profile.two-factor.enable',
    //     'uses' => 'ProfileController@enableTwoFactorAuth'
    // ]);
    // Route::post('profile/two-factor/disable', [
    //     'as' => 'frontend.profile.two-factor.disable',
    //     'uses' => 'ProfileController@disableTwoFactorAuth'
    // ]);
    // Route::get('profile/sessions', [
    //     'as' => 'frontend.profile.sessions',
    //     'uses' => 'ProfileController@sessions'
    // ]);
    // Route::delete('profile/sessions/{session}/invalidate', [
    //     'as' => 'frontend.profile.sessions.invalidate',
    //     'uses' => 'ProfileController@invalidateSession'
    // ]);
	
	// Route::get('profile/returns', [
    //     'as' => 'frontend.profile.returns',
    //     'uses' => 'ProfileController@returns'
    // ]);

    // Route::get('profile/jackpots', [
    //     'as' => 'frontend.profile.jackpots',
    //     'uses' => 'ProfileController@jackpots'
    // ]);

    // Route::get('profile/pincode', [
    //     'as' => 'frontend.profile.pincode',
    //     'uses' => 'ProfileController@pincode'
    // ]);

    // Route::get('setlang/{lang}', [
    //     'as' => 'frontend.setlang',
    //     'uses' => 'ProfileController@setlang'
    // ]);
			
	
	/**
     * Games routes
    */

	Route::get('/', [
        'as' => 'frontend.game.list',
        'uses' => 'GamesController@index'
    ]);
	Route::get('/search', [
        'as' => 'frontend.game.search',
        'uses' => 'GamesController@search'
    ]);
    Route::get('providers/tp/{gamecode}', [
        'as' => 'frontend.providers.tp.render',
        'uses' => 'RenderingController@theplusrender'
    ]);
    Route::get('followgame/{provider}/{gamecode}', [
        'as' => 'frontend.providers.render',
        'uses' => 'RenderingController@gamerenderv2'
    ]);
    Route::get('providers/pp/{gamecode}', [
        'as' => 'frontend.providers.pp.render',
        'uses' => 'RenderingController@pragmaticrender'
    ]);
    Route::get('providers/hbn/{gamecode}', [
        'as' => 'frontend.providers.hbn.render',
        'uses' => 'RenderingController@habanerorender'
    ]);
    Route::get('providers/cq9/{gamecode}', [
        'as' => 'frontend.providers.cq9.render',
        'uses' => 'RenderingController@cq9render'
    ]);
    Route::get('providers/bng/{gamecode}', [
        'as' => 'frontend.providers.bng.render',
        'uses' => 'RenderingController@booongorender'
    ]);
    Route::get('providers/waiting/{provider}/{gamecode}', [
        'as' => 'frontend.providers.waiting',
        'uses' => 'RenderingController@waiting'
    ])->middleware('simultaneous:1');

    Route::get('providers/launch/{requestid}', [
        'as' => 'frontend.providers.launch',
        'uses' => 'RenderingController@launch'
    ]);


	/*
	Route::get('games', [
        'as' => 'frontend.game.list',
        'uses' => 'GamesController@index'
    ]);	
	*/
	
	Route::get('categories/{category1}', [
        'as' => 'frontend.game.list.category',
        'uses' => 'GamesController@index'
    ]);
	
	Route::get('categories/{category1}/{category2}', [
        'as' => 'frontend.game.list.category_level2',
        'uses' => 'GamesController@index'
    ]);

    Route::get('setpage.json', [
        'as' => 'frontend.category.setpage',
        'uses' => 'GamesController@setpage'
    ]);
    
	Route::get('game/{game}', [
        'as' => 'frontend.game.go',
        'uses' => 'GamesController@go'
    ]);	
	Route::post('game/{game}/server', [
        'as' => 'frontend.game.server',
        'uses' => 'GamesController@server'
    ]);    
	Route::get('game_result', [
        'as' => 'frontend.game_result',
        'uses' => 'GamesController@game_result',
    ]);
    Route::get('pay_table', [
        'as' => 'frontend.pay_table',
        'uses' => 'GamesController@pay_table',
    ]);
	/*
	Route::prefix('payment')->group(function () { 
		Route::post('/piastrix/result', [
			'as' => 'payment.piastrix.result',
			'uses' => 'PaymentController@piastrix'
		]);
	});
	*/
    
    //added by khs
    Route::post('api/login', [
        'as' => 'frontend.api.login',
        'uses' => 'ApiController@login',
    ]);
    Route::post('api/balance', [
        'as' => 'frontend.api.balance',
        'uses' => 'ApiController@getbalance',
    ]);
    Route::post('api/getgamelist', [
        'as' => 'frontend.api.getgamelist',
        'uses' => 'ApiController@getgamelist',
    ]);

    Route::post('api/getgamelist_vi', [
        'as' => 'frontend.api.getgamelist_vi',
        'uses' => 'ApiController@getgamelist_vi',
    ]);

    Route::post('bo/getgamelist', [
        'as' => 'frontend.api.bogetgamelist',
        'uses' => 'ApiController@bo_getgamelist',
    ]);
    Route::post('bo/getgamedetail', [
        'as' => 'frontend.api.bogetgamedetail',
        'uses' => 'ApiController@bo_getgamedetail',
    ]);
    //game start
    Route::get('gamestart/endpoint', [
        'as' => 'frontend.api.bostartgame',
        'uses' => 'GamesController@startGameWithToken',
    ]);

    Route::get('gamestart/go/htmlGame.go', [
        'as' => 'frontend.game.startgame',
        'uses' => 'GamesController@startGameWithiFrame',
    ]);
    Route::get('gamestart/pball_go', [
        'as' => 'frontend.game.startpball',
        'uses' => 'GamesController@pball_go',
    ]);

    Route::post('api/getgamelink', [
        'as' => 'frontend.api.getgamelink',
        'uses' => 'ApiController@getgamelink',
    ]);

    Route::get('api/website/getgamelink', [
        'as' => 'frontend.api.website.gamelink',
        'uses' => 'ApiController@sitegamelink',
    ]);

    //added by shev
    Route::post('api/change_bank_account', [
        'as' => 'frontend.api.change_bank_account',
        'uses' => 'ApiController@changeBankAccount',
    ]);
    Route::post('api/deposit', [
        'as' => 'frontend.api.olddeposit',
        'uses' => 'ApiController@olddeposit',
    ]);
    Route::post('api/withdraw', [
        'as' => 'frontend.api.oldwithdraw',
        'uses' => 'ApiController@oldwithdraw',
    ]);
    Route::post('api/addbalance', [
        'as' => 'frontend.api.deposit',
        'uses' => 'ApiController@deposit',
    ]);
    Route::post('api/outbalance', [
        'as' => 'frontend.api.withdraw',
        'uses' => 'ApiController@withdraw',
    ]);
    Route::post('api/depositAccount', [
        'as' => 'frontend.api.depositAccount',
        'uses' => 'ApiController@depositAccount',
    ]);
    Route::post('api/readMsg', [
        'as' => 'frontend.api.readmsg',
        'uses' => 'ApiController@readMessage',
    ]);
    Route::post('api/writeMsg', [
        'as' => 'frontend.api.writemsg',
        'uses' => 'ApiController@writeMessage',
    ]);
    Route::post('api/messages', [
        'as' => 'frontend.api.msglist',
        'uses' => 'ApiController@msglist',
    ]);
    Route::post('api/deleteMsg', [
        'as' => 'frontend.api.deletemsg',
        'uses' => 'ApiController@deleteMessage',
    ]);
    Route::post('api/deal_withdraw', [
        'as' => 'frontend.api.deal_withdraw',
        'uses' => 'ApiController@withdrawDealMoney',
    ]);
    Route::post('api/convert_deal_balance', [
        'as' => 'frontend.api.convert_deal_balance',
        'uses' => 'ApiController@convertDealBalance',
    ])->middleware('simultaneous:1');

    Route::post('api/checkid', [
        'as' => 'frontend.api.checkid',
        'uses' => 'ApiController@checkId',
    ]);

    Route::post('api/join', [
        'as' => 'frontend.api.join',
        'uses' => 'ApiController@postJoin'
    ]); 

    Route::post('api/inouthistory', [
        'as' => 'frontend.api.inouthistory',
        'uses' => 'ApiController@inoutHistory'
    ]); 

    Route::post('api/notices', [
        'as' => 'frontend.api.notices',
        'uses' => 'ApiController@notices'
    ]); 


    Route::get('/api/stat_game_balance', [
        'as' => 'backend.game_stat.balance',
        'uses' => 'ApiController@stat_game_balance',
    ]);

    Route::post('api/allow_in_out', [
        'as' => 'frontend.api.allow_in_out',
        'uses' => 'ApiController@allowInOut',
    ]);

    Route::get('api/wait_in_out/{id}', [
        'as' => 'frontend.api.wait_in_out',
        'uses' => 'ApiController@waitInOut',
    ]);

    Route::post('api/reject_in_out', [
        'as' => 'frontend.api.reject_in_out',
        'uses' => 'ApiController@rejectInOut',
    ]);

    Route::get('api/inoutlist.json', [
        'as' => 'frontend.api.inoutlist',
        'uses' => 'ApiController@inoutList_json',
    ]);

    //**************/
});

/**
*
*
*
******************* BACKEND
*
*
*
*/

Route::prefix('backend')->group(function () {
    Route::namespace('Backend')->group(function () {
        Route::get('login', [
            'as' => 'backend.auth.login',
            'uses' => 'Auth\AuthController@getLogin'
        ]);
        Route::post('login', [
            'as' => 'backend.auth.login.post',
            'uses' => 'Auth\AuthController@postLogin'
        ]);
    });
});

Route::prefix('backend')->middleware(['auth'])->group(function () {
	Route::namespace('Backend')->group(function () {


	Route::get('logout', [
		'as' => 'backend.auth.logout',
		'uses' => 'Auth\AuthController@getLogout'
	]);


	
	
    /**
     * Dashboard
     */


    Route::get('/search', [
        'as' => 'backend.search',
        'uses' => 'DashboardController@search',
        'middleware' => 'permission:full.search',
    ]);

    Route::get('/', [
        'as' => 'backend.dashboard',
        'uses' => 'DashboardController@index',
        //
    ]);
	Route::get('/stat_game', [
        'as' => 'backend.game_stat',
        'uses' => 'DashboardController@game_stat',
        'middleware' => 'permission:stats.game',
    ]);
    
	Route::delete('/game_stat/clear', [
        'as' => 'backend.game_stat.clear',
        'uses' => 'DashboardController@game_stat_clear'
    ]);
	Route::get('/bank_stat', [
        'as' => 'backend.bank_stat',
        'uses' => 'DashboardController@bank_stat',
        'middleware' => 'permission:stats.bank',
    ]);
	Route::get('/shop_stat', [
        'as' => 'backend.shop_stat',
        'uses' => 'DashboardController@shop_stat',
        'middleware' => 'permission:stats.shop',
    ]);
	Route::get('/shift_stat', [
        'as' => 'backend.shift_stat',
        'uses' => 'DashboardController@shift_stat',
        'middleware' => 'permission:stats.shift',
    ]);
	Route::get('/live', [
        'as' => 'backend.live_stat',
        'uses' => 'DashboardController@live_stat',
        'middleware' => 'permission:stats.live',
    ]);
    Route::get('/deal_stat', [
        'as' => 'backend.deal_stat',
        'uses' => 'DashboardController@deal_stat',
        'middleware' => 'permission:stats.shop',
    ]);
    Route::delete('/deal_stat/clear', [
        'as' => 'backend.deal_stat.clear',
        'uses' => 'DashboardController@deal_stat_clear'
    ]);
	
	
	Route::get('/start_shift', [
        'as' => 'backend.start_shift',
        'uses' => 'DashboardController@start_shift'
    ]);

    //added by shev 2021-02-15
    Route::get('/adjustment_partner', [
        'as' => 'backend.adjustment_partner',
        'uses' => 'DashboardController@adjustment_partner',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_ggr', [
        'as' => 'backend.adjustment_ggr',
        'uses' => 'DashboardController@adjustment_ggr',
        'middleware' => 'permission:stats.game',
    ]);
    Route::get('/process_ggr', [
        'as' => 'backend.process_ggr',
        'uses' => 'DashboardController@process_ggr',
        'middleware' => 'permission:stats.game',
    ]);
    Route::get('/reset_ggr', [
        'as' => 'backend.reset_ggr',
        'uses' => 'DashboardController@reset_ggr',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_daily', [
        'as' => 'backend.adjustment_daily',
        'uses' => 'DashboardController@adjustment_daily',
    ]);

    Route::get('/adjustment_monthly', [
        'as' => 'backend.adjustment_monthly',
        'uses' => 'DashboardController@adjustment_monthly',
    ]);

    Route::get('/adjustment_game', [
        'as' => 'backend.adjustment_game',
        'uses' => 'DashboardController@adjustment_game',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_month', [
        'as' => 'backend.adjustment_month',
        'uses' => 'DashboardController@adjustment_month',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_shift', [
        'as' => 'backend.adjustment_shift',
        'uses' => 'DashboardController@adjustment_shift',
        'middleware' => 'permission:stats.game',
    ]);

    Route::get('/adjustment_create_shift', [
        'as' => 'backend.adjustment_create_shift',
        'uses' => 'DashboardController@adjustment_create_shift',
        'middleware' => 'permission:stats.game',
    ]);    

    Route::get('/adjustment_shift_stat', [
        'as' => 'backend.adjustment_shift_stat',
        'uses' => 'DashboardController@adjustment_shift_stat'
    ]);

    Route::get('/in_out_request', [
        'as' => 'backend.in_out_request',
        'uses' => 'DashboardController@in_out_request'
    ]);
    Route::get('/in_out_history', [
        'as' => 'backend.in_out_history',
        'uses' => 'DashboardController@in_out_history'
    ]);    

    Route::get('/in_out_manage/{type}', [
        'as' => 'backend.in_out_manage',
        'uses' => 'DashboardController@in_out_manage'
    ]);

    Route::get('/bonus/pplist', [
        'as' => 'backend.bonus.pp',
        'uses' => 'BonusController@pp_index'
    ]);
    Route::get('/bonus/ppadd', [
        'as' => 'backend.bonus.ppadd',
        'uses' => 'BonusController@pp_add'
    ]);
    Route::post('/bonus/ppstore', [
        'as' => 'backend.bonus.ppstore',
        'uses' => 'BonusController@pp_store'
    ]);
    Route::delete('/bonus/ppcancel/{bonusCode}', [
        'as' => 'backend.bonus.ppcancel',
        'uses' => 'BonusController@pp_cancel'
    ]);
    Route::get('/bonus/bnglist', [
        'as' => 'backend.bonus.bng',
        'uses' => 'BonusController@bng_index'
    ]);
    Route::get('/bonus/bngadd', [
        'as' => 'backend.bonus.bngadd',
        'uses' => 'BonusController@bng_add'
    ]);
    Route::post('/bonus/bngstore', [
        'as' => 'backend.bonus.bngstore',
        'uses' => 'BonusController@bng_store'
    ]);
    Route::delete('/bonus/bngcancel/{bonusCode}', [
        'as' => 'backend.bonus.bngcancel',
        'uses' => 'BonusController@bng_cancel'
    ]);

   
	
    /**
     * User Profile
     */

    Route::get('profile', [
        'as' => 'backend.profile',
        'uses' => 'ProfileController@index'
    ]);
    Route::get('profile/activity', [
        'as' => 'backend.profile.activity',
        'uses' => 'ProfileController@activity'
    ]);
    Route::put('profile/details/update', [
        'as' => 'backend.profile.update.details',
        'uses' => 'ProfileController@updateDetails'
    ]);
    Route::post('profile/avatar/update', [
        'as' => 'backend.profile.update.avatar',
        'uses' => 'ProfileController@updateAvatar'
    ]);
    Route::post('profile/avatar/update/external', [
        'as' => 'backend.profile.update.avatar-external',
        'uses' => 'ProfileController@updateAvatarExternal'
    ]);
    Route::put('profile/login-details/update', [
        'as' => 'backend.profile.update.login-details',
        'uses' => 'ProfileController@updateLoginDetails'
    ]);
    Route::post('profile/two-factor/enable', [
        'as' => 'backend.profile.two-factor.enable',
        'uses' => 'ProfileController@enableTwoFactorAuth'
    ]);
    Route::post('profile/two-factor/disable', [
        'as' => 'backend.profile.two-factor.disable',
        'uses' => 'ProfileController@disableTwoFactorAuth'
    ]);
    Route::get('profile/sessions', [
        'as' => 'backend.profile.sessions',
        'uses' => 'ProfileController@sessions'
    ]);
    Route::delete('profile/sessions/{session}/invalidate', [
        'as' => 'backend.profile.sessions.invalidate',
        'uses' => 'ProfileController@invalidateSession'
    ]);
	Route::match(['get','post'], 'profile/setshop', [
        'as' => 'backend.profile.setshop',
        'uses' => 'ProfileController@setshop'
    ]);

    /**
     * User Management
    */
	
    Route::get('user', [
        'as' => 'backend.user.list',
        'uses' => 'UsersController@index',
        'middleware' => 'permission:users.manage'
    ]);
    Route::get('join', [
        'as' => 'backend.user.join',
        'uses' => 'UsersController@join',
        'middleware' => 'permission:users.manage'
    ]);
    Route::post('processjoin', [
        'as' => 'backend.user.processjoin',
        'uses' => 'UsersController@processJoin',
    ]);
    Route::get('tree', [
        'as' => 'backend.user.tree',
        'uses' => 'UsersController@tree',
        'middleware' => 'permission:users.tree'
    ]);

    Route::get('black', [
        'as' => 'backend.black.list',
        'uses' => 'UsersController@blacklist',
    ]);    
    Route::get('black/create', [
        'as' => 'backend.black.create',
        'uses' => 'UsersController@blackcreate',
    ]);
    Route::post('black/create', [
        'as' => 'backend.black.store',
        'uses' => 'UsersController@blackstore',
    ]);
    Route::get('black/{blackid}/edit', [
        'as' => 'backend.black.edit',
        'uses' => 'UsersController@blackedit',
    ]);  
    Route::post('black/{blackid}/update', [
        'as' => 'backend.black.update',
        'uses' => 'UsersController@blackupdate',
    ]);
    Route::delete('black/{blackid}/remove', [
        'as' => 'backend.black.remove',
        'uses' => 'UsersController@blackremove',
    ]);

    Route::get('partner/{role_id}', [
        'as' => 'backend.user.partner',
        'uses' => 'UsersController@partner',
    ]);

    Route::get('statistics', [
        'as' => 'backend.statistics',
        'uses' => 'DashboardController@statistics',
        'middleware' => 'permission:stats.pay',
    ]);	
    Route::get('partner_statistics', [
        'as' => 'backend.statistics_partner',
        'uses' => 'DashboardController@statistics_partner',
        'middleware' => 'permission:stats.pay',
    ]);
    Route::get('mileage_stat', [
        'as' => 'backend.mileage_stat',
        'uses' => 'DashboardController@mileage_stat',
        'middleware' => 'permission:stats.pay',
    ]);	
    Route::post('partner/master/reset_ggr', [
        'uses' => 'UsersController@reset_ggr',
		'as' => 'backend.user.partner.reset_ggr',
    ]);
	Route::post('profile/balance/update', [
        'uses' => 'UsersController@updateBalance',
		'as' => 'backend.user.balance.update',
		'middleware' => 'permission:users.balance.manage'
    ])->middleware('simultaneous:1');
    Route::get('profile/balance/setbonus/{userid}', [
        'uses' => 'UsersController@setBonusSetting',
		'as' => 'backend.user.balance.bonus',
		'middleware' => 'permission:users.balance.manage'
    ]);
    Route::get('user/create', [
        'as' => 'backend.user.create',
        'uses' => 'UsersController@create',
        'middleware' => 'permission:users.add'
    ]);
    Route::get('user/createuserfromcsv', [
        'as' => 'backend.user.createuserfromcsv',
        'uses' => 'UsersController@createuserfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/createuserfromcsv', [
        'as' => 'backend.user.storeuserfromcsv',
        'uses' => 'UsersController@storeuserfromcsv',
        'middleware' => 'permission:users.add'
    ]);    
    Route::get('user/createpartnerfromcsv', [
        'as' => 'backend.user.createpartnerfromcsv',
        'uses' => 'UsersController@createpartnerfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/createpartnerfromcsv', [
        'as' => 'backend.user.storepartnerfromcsv',
        'uses' => 'UsersController@storepartnerfromcsv',
        'middleware' => 'permission:users.add'
    ]);
    Route::post('user/create', [
        'as' => 'backend.user.store',
        'uses' => 'UsersController@store',
        'middleware' => 'permission:users.add'
    ]);

	Route::get('user/{user}/stat', [
        'as' => 'backend.user.stat',
        'uses' => 'UsersController@statistics'
    ]);
    Route::post('user/mass', [
        'as' => 'backend.user.massadd',
        'uses' => 'UsersController@massadd',
        'middleware' => 'permission:users.add'
    ]);
    Route::get('user/{user}/show', [
        'as' => 'backend.user.show',
        'uses' => 'UsersController@view'
    ]);
    Route::get('user/{user}/profile', [
        'as' => 'backend.user.edit',
        'uses' => 'UsersController@edit'
    ]);
    Route::put('user/{user}/update/details', [
        'as' => 'backend.user.update.details',
        'uses' => 'UsersController@updateDetails'
    ]);
    Route::get('user/{user}/update/reset_confirm_pwd', [
        'as' => 'backend.user.update.resetpwd',
        'uses' => 'UsersController@resetConfirmPwd'
    ]);    
    Route::post('user/{user}/update/move', [
        'as' => 'backend.user.update.move',
        'uses' => 'UsersController@move'
    ]);
    Route::put('user/{user}/update/login-details', [
        'as' => 'backend.user.update.login-details',
        'uses' => 'UsersController@updateLoginDetails'
    ]);
    Route::delete('user/{user}/delete', [
        'as' => 'backend.user.delete',
        'uses' => 'UsersController@delete',
		'middleware' => 'permission:users.delete'
    ]);
    Route::delete('user/{user}/hard_delete', [
        'as' => 'backend.user.hard_delete',
        'uses' => 'UsersController@hard_delete',
        'middleware' => 'permission:users.delete'
    ]);
    Route::post('user/{user}/update/avatar', [
        'as' => 'backend.user.update.avatar',
        'uses' => 'UsersController@updateAvatar'
    ]);
    Route::post('user/{user}/update/address', [
        'as' => 'backend.user.update.address',
        'uses' => 'UsersController@updateAddress'
    ]);
    Route::post('user/{user}/update/avatar/external', [
        'as' => 'backend.user.update.avatar.external',
        'uses' => 'UsersController@updateAvatarExternal'
    ]);
    Route::get('user/{user}/sessions', [
        'as' => 'backend.user.sessions',
        'uses' => 'UsersController@sessions'
    ]);
    Route::delete('user/{user}/sessions/{session}/invalidate', [
        'as' => 'backend.user.sessions.invalidate',
        'uses' => 'UsersController@invalidateSession'
    ]);
    Route::post('user/{user}/two-factor/enable', [
        'as' => 'backend.user.two-factor.enable',
        'uses' => 'UsersController@enableTwoFactorAuth'
    ]);
    Route::post('user/{user}/two-factor/disable', [
        'as' => 'backend.user.two-factor.disable',
        'uses' => 'UsersController@disableTwoFactorAuth'
    ]);

        Route::delete('user/action/{action}', [
            'as' => 'backend.user.action',
            'uses' => 'UsersController@action',
        ]);
	
	/**
     * Games routes
    */

	Route::get('game', [
        'as' => 'backend.game.list',
        'uses' => 'GamesController@index',
        'middleware' => 'permission:games.manage'
    ]);	
    Route::get('gamebank', [
        'as' => 'backend.game.bank',
        'uses' => 'GamesController@bank',
        'middleware' => 'permission:games.manage'
    ]);	

    Route::get('bonusbank', [
        'as' => 'backend.game.bonusbank',
        'uses' => 'GamesController@bonusbank',
        'middleware' => 'permission:games.manage'
    ]);	

	Route::get('games.json', [
        'as' => 'backend.game.list.json',
        'uses' => 'GamesController@index_json'
    ]);
    Route::get('game/create', [
        'as' => 'backend.game.create',		
        'uses' => 'GamesController@create',
        'middleware' => 'permission:games.add'
    ]);
    Route::post('game/create', [
        'as' => 'backend.game.store',		
        'uses' => 'GamesController@store',
        'middleware' => 'permission:games.add'
    ]);
    Route::get('game/{game}/show', [
        'as' => 'backend.game.show',		
        'uses' => 'GamesController@view',
    ]);	
	Route::get('game/{game}', [
        'as' => 'backend.game.go',
        'uses' => 'GamesController@go'
    ]);	
	Route::post('/game/{game}/server', [
        'as' => 'backend.game.server',
        'uses' => 'GamesController@server'
    ]);
    Route::get('game/{game}/edit', [
        'as' => 'backend.game.edit',		
        'uses' => 'GamesController@edit',
        'middleware' => 'permission:games.edit'
    ]);	
	Route::post('game/{game}/update', [
        'as' => 'backend.game.update',		
        'uses' => 'GamesController@update',
    ]);	
	Route::delete('game/{game}/delete', [
        'as' => 'backend.game.delete',		
        'uses' => 'GamesController@delete',
        'middleware' => 'permission:games.delete'
    ]);	
	Route::post('game/categories', [
        'as' => 'backend.game.categories',		
        'uses' => 'GamesController@categories',
    ]);
    Route::post('game/update/mass', [
        'as' => 'backend.game.mass',
        'uses' => 'GamesController@mass',
        'middleware' => 'permission:games.edit'
    ]);


    Route::post('gamebanks_add', [
       'as' => 'backend.game.gamebanks_add',
        'uses' => 'GamesController@gamebanks_add',
    ]);
    Route::post('gamebanks_setting', [
        'as' => 'backend.game.gamebanks_setting',
         'uses' => 'GamesController@gamebanks_setting',
     ]);    
    Route::get('gamebanks_clear', [
        'as' => 'backend.game.gamebanks_clear',
        'uses' => 'GamesController@gamebanks_clear',
    ]);



        /**
     * Categories routes
     */

	Route::get('category', [
        'as' => 'backend.category.list',
        'uses' => 'CategoriesController@index',
        'middleware' => 'permission:categories.manage'
    ]);	
    Route::get('category/create', [
        'as' => 'backend.category.create',		
        'uses' => 'CategoriesController@create',
        'middleware' => 'permission:categories.add'
    ]);
    Route::post('category/create', [
        'as' => 'backend.category.store',		
        'uses' => 'CategoriesController@store',
        'middleware' => 'permission:categories.add'
    ]);    
    Route::get('category/{category}/edit', [
        'as' => 'backend.category.edit',		
        'uses' => 'CategoriesController@edit',
    ]);	
	Route::post('category/{category}/update', [
        'as' => 'backend.category.update',		
        'uses' => 'CategoriesController@update',
    ]);	
	Route::delete('category/{category}/delete', [
        'as' => 'backend.category.delete',		
        'uses' => 'CategoriesController@delete',
        'middleware' => 'permission:categories.delete'
    ]);
    Route::get('category/{category}/show', [
        'as' => 'backend.category.show',		
        'uses' => 'CategoriesController@view',
    ]);	
	/**
     * Categories routes
     */

	Route::get('shops', [
        'as' => 'backend.shop.list',
        'uses' => 'ShopsController@index',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::get('shops/create', [
        'as' => 'backend.shop.create',
        'uses' => 'ShopsController@create',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::post('shops/create', [
        'as' => 'backend.shop.store',
        'uses' => 'ShopsController@store',
        'middleware' => 'permission:shops.manage'
    ]);

    Route::get('shops/admin/create', [
        'as' => 'backend.shop.admin_create',
        'uses' => 'ShopsController@admin_create',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::post('shops/admin/create', [
        'as' => 'backend.shop.admin_store',
        'uses' => 'ShopsController@admin_store',
        'middleware' => 'permission:shops.manage'
    ]);

    Route::get('shops/{shop}/edit', [
        'as' => 'backend.shop.edit',
        'uses' => 'ShopsController@edit',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::post('shops/{shop}/update', [
        'as' => 'backend.shop.update',
        'uses' => 'ShopsController@update',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::post('shops/balance', [
        'as' => 'backend.shop.balance',
        'uses' => 'ShopsController@balance',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::delete('shops/{shop}/delete', [
        'as' => 'backend.shop.delete',
        'uses' => 'ShopsController@delete',
        'middleware' => 'permission:shops.manage'
    ]);
	Route::delete('shops/{shop}/hard_delete', [
        'as' => 'backend.shop.hard_delete',
        'uses' => 'ShopsController@hard_delete',
        'middleware' => 'permission:shops.manage'
    ]);
    Route::delete('shops/{shop}/action/{action}', [
        'as' => 'backend.shop.action',
        'uses' => 'ShopsController@action',
        'middleware' => 'permission:shops.manage'
    ]);


    /**
     * notices routes
     */

    Route::get('notices', [
        'as' => 'backend.notice.list',
        'uses' => 'NoticesController@index',
    ]);
    Route::get('notices/create', [
        'as' => 'backend.notice.create',
        'uses' => 'NoticesController@create',
    ]);
    Route::post('notices/create', [
        'as' => 'backend.notice.store',
        'uses' => 'NoticesController@store',
    ]);
    Route::get('notices/{notice}/edit', [
        'as' => 'backend.notice.edit',
        'uses' => 'NoticesController@edit',
    ]);
    Route::post('notices/{notice}/update', [
        'as' => 'backend.notice.update',
        'uses' => 'NoticesController@update',
    ]);
    Route::delete('notices/{notice}/delete', [
        'as' => 'backend.notice.delete',
        'uses' => 'NoticesController@delete',
    ]);
    /**
     * messages routes
     */

    Route::get('messages', [
        'as' => 'backend.msg.list',
        'uses' => 'MessageController@index',
    ]);
    Route::get('messages/create', [
        'as' => 'backend.msg.create',
        'uses' => 'MessageController@create',
    ]);
    Route::post('messages/create', [
        'as' => 'backend.msg.store',
        'uses' => 'MessageController@store',
    ]);
    Route::delete('messages/{message}/delete', [
        'as' => 'backend.msg.delete',
        'uses' => 'MessageController@delete',
    ]);
        /**
         * websites routes
         */

        Route::get('websites', [
            'as' => 'backend.website.list',
            'uses' => 'WebsitesController@index',
        ]);
        Route::get('websites/create', [
            'as' => 'backend.website.create',
            'uses' => 'WebsitesController@create',
        ]);
        Route::post('websites/create', [
            'as' => 'backend.website.store',
            'uses' => 'WebsitesController@store',
        ]);
        Route::get('websites/{website}/edit', [
            'as' => 'backend.website.edit',
            'uses' => 'WebsitesController@edit',
        ]);
        Route::post('websites/{website}/update', [
            'as' => 'backend.website.update',
            'uses' => 'WebsitesController@update',
        ]);
        Route::delete('websites/{website}/delete', [
            'as' => 'backend.website.delete',
            'uses' => 'WebsitesController@delete',
        ]);

        /**
         * Happyhours routes
         */

        Route::get('happyhours', [
            'as' => 'backend.happyhour.list',
            'uses' => 'HappyHourController@index',
            'middleware' => 'permission:happyhours.manage'
        ]);
        Route::get('happyhours/create', [
            'as' => 'backend.happyhour.create',
            'uses' => 'HappyHourController@create',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::post('happyhours/create', [
            'as' => 'backend.happyhour.store',
            'uses' => 'HappyHourController@store',
            'middleware' => 'permission:happyhours.add'
        ]);
        Route::get('happyhours/{happyhour}/edit', [
            'as' => 'backend.happyhour.edit',
            'uses' => 'HappyHourController@edit',
        ]);
        Route::post('happyhours/{happyhour}/update', [
            'as' => 'backend.happyhour.update',
            'uses' => 'HappyHourController@update',
        ]);
        Route::delete('happyhours/{happyhour}/delete', [
            'as' => 'backend.happyhour.delete',
            'uses' => 'HappyHourController@delete',
            'middleware' => 'permission:happyhours.delete'
        ]);

        /**
         * Roles & Permissions
         */

        Route::get('jpgame', [
            'as' => 'backend.jpgame.list',
            'uses' => 'JPGController@index',
            //'middleware' => 'permission:jackpots.manage',
        ]);
        Route::get('jpgame/create', [
            'as' => 'backend.jpgame.create',
            'uses' => 'JPGController@create',
            //'middleware' => 'permission:jackpots.add'
        ]);
        Route::post('jpgame/create', [
            'as' => 'backend.jpgame.store',
            'uses' => 'JPGController@store',
            //'middleware' => 'permission:jackpots.add'
        ]);
        Route::get('jpgame/{jackpot}/edit', [
            'as' => 'backend.jpgame.edit',
            'uses' => 'JPGController@edit',
        ]);
        Route::post('jpgame/{jackpot}/update', [
            'as' => 'backend.jpgame.update',
            'uses' => 'JPGController@update',
        ]);
        Route::post('jpgame/balance', [
            'as' => 'backend.jpgame.balance',
            'uses' => 'JPGController@balance',
        ]);




        /**
     * Roles & Permissions
     */

    Route::get('role', [
        'as' => 'backend.role.index',
        'uses' => 'RolesController@index',
        'middleware' => 'permission:roles.manage'
    ]);
    Route::get('role/create', [
        'as' => 'backend.role.create',
        'uses' => 'RolesController@create'
    ]);
    Route::post('role/store', [
        'as' => 'backend.role.store',
        'uses' => 'RolesController@store'
    ]);
    Route::get('role/{role}/edit', [
        'as' => 'backend.role.edit',
        'uses' => 'RolesController@edit'
    ]);
    Route::put('role/{role}/update', [
        'as' => 'backend.role.update',
        'uses' => 'RolesController@update'
    ]);
    Route::delete('role/{role}/delete', [
        'as' => 'backend.role.delete',
        'uses' => 'RolesController@delete'
    ]);	
	
    Route::post('permission/save', [
        'as' => 'backend.permission.save',
        'uses' => 'PermissionsController@saveRolePermissions'
    ]);
	
	/**
     * Permissions
     */
	 
	Route::get('permission', [
        'as' => 'backend.permission.index',
        'uses' => 'PermissionsController@index',
        'middleware' => 'permission:permissions.manage'
    ]);
    Route::get('permission/create', [
        'as' => 'backend.permission.create',
        'uses' => 'PermissionsController@create',
        'middleware' => 'permission:permissions.add'
    ]);
    Route::post('permission/store', [
        'as' => 'backend.permission.store',
        'uses' => 'PermissionsController@store',
        'middleware' => 'permission:permissions.add'
    ]);
    Route::get('permission/{permission}/edit', [
        'as' => 'backend.permission.edit',
        'uses' => 'PermissionsController@edit'
    ]);
    Route::put('permission/{permission}/update', [
        'as' => 'backend.permission.update',
        'uses' => 'PermissionsController@update'
    ]);
    Route::delete('permission/{permission}/delete', [
        'as' => 'backend.permission.delete',
        'uses' => 'PermissionsController@delete'
    ]);	
	

    /**
     * Settings
     */

    Route::get('settings', [
        'as' => 'backend.settings.general',
        'uses' => 'SettingsController@general',
        'middleware' => 'permission:settings.general',
    ]);
    Route::post('settings/general', [
        'as' => 'backend.settings.general.update',
        'uses' => 'SettingsController@update',
        'middleware' => 'permission:settings.general'
    ]);

    Route::get('settings/auth', [
        'as' => 'backend.settings.auth',
        'uses' => 'SettingsController@auth',
        'middleware' => 'permission:settings.auth'
    ]);
    Route::post('settings/auth', [
        'as' => 'backend.settings.auth.update',
        'uses' => 'SettingsController@update',
        'middleware' => 'permission:settings.auth'
    ]);
	
	Route::get('generator', [
        'as' => 'backend.settings.generator',
        'uses' => 'SettingsController@generator',
        'middleware' => 'permission:settings.generator'
    ]);
	
	Route::post('generator', [
        'as' => 'backend.settings.generator.post',
        'uses' => 'SettingsController@generator',
        'middleware' => 'permission:settings.generator'
    ]);

    Route::put('shops/block', [
        'as' => 'backend.settings.shop_block',
        'uses' => 'SettingsController@shop_block',
        'middleware' => 'permission:shops.block'
    ]);

    Route::put('shops/unblock', [
        'as' => 'backend.settings.shop_unblock',
        'uses' => 'SettingsController@shop_unblock',
        'middleware' => 'permission:shops.unblock'
    ]);

    Route::put('settings/sync', [
        'as' => 'backend.settings.sync',
        'uses' => 'SettingsController@sync'
    ]);
	

    /**
     * Activity Log
     */

    Route::get('activity', [
        'as' => 'backend.activity.index',
        'uses' => 'ActivityController@index',
        'middleware' => 'permission:users.activity',
    ]);
    Route::get('activity/user/{user}/log', [
        'as' => 'backend.activity.user',
        'uses' => 'ActivityController@userActivity'
    ]);

    Route::delete('activity/clear', [
        'as' => 'backend.activity.clear',
        'uses' => 'ActivityController@clear',
    ]);

    //added by shev

    // Route::post('api/generateFreespin', [
    //     'as' => 'backend.api.generateFreespin',
    //     'uses' => 'SpinGeneraterController@generateFreespin',
    // ]);
    // Route::post('api/generateFreespin', [
    //     'as' => 'backend.api.generateFreespin',
    //     'uses' => 'DancingDrumSpinGeneraterController@generateFreespin',
    // ]);

    Route::post('api/generateFreespin', [
        'as' => 'backend.api.generateFreespin',
        'uses' => 'EightFortuneSpinGeneraterController@generateFreespin',
    ]);

	});
});




/**
 * CQ9 Game Provider
 */
Route::group(['middleware' => 'cq9', 'prefix' => 'cq9',], function () {
    Route::post('/transaction/game/rollout', 'GameProviders\CQ9Controller@rollout');
    Route::post('/transaction/game/takeall', 'GameProviders\CQ9Controller@takeall');
    Route::post('/transaction/game/rollin', 'GameProviders\CQ9Controller@rollin');
	Route::post('/transaction/game/bet', 'GameProviders\CQ9Controller@bet');
	Route::post('/transaction/game/endround', 'GameProviders\CQ9Controller@endround');
    Route::post('/transaction/game/debit', 'GameProviders\CQ9Controller@debit');
    Route::post('/transaction/game/credit', 'GameProviders\CQ9Controller@credit');
    Route::post('/transaction/game/refund', 'GameProviders\CQ9Controller@refund');
    Route::post('/transaction/user/payoff', 'GameProviders\CQ9Controller@payoff');
	Route::get('/transaction/record/{mtcode}', 'GameProviders\CQ9Controller@record');
    Route::get('/transaction/balance/{account}', 'GameProviders\CQ9Controller@balance');
    Route::get('/player/check/{account}', 'GameProviders\CQ9Controller@checkplayer');
});

Route::get('clientinfo', 'GameProviders\CQ9Controller@clientInfo');
Route::get('clientinfo/toggle', 'GameProviders\CQ9Controller@toggle');
Route::get('platform/', 'GameProviders\CQ9Controller@cq9History');
Route::get('playerodh5/', 'GameProviders\CQ9Controller@cq9PlayerOrder');
Route::get('api/player_betting/search_time', 'GameProviders\CQ9Controller@searchTime');
Route::get('api/player_betting/detail_link', 'GameProviders\CQ9Controller@detailLink');
Route::get('api/inquire/v1/db/wager', 'GameProviders\CQ9Controller@wager');
Route::get('playerodh5/gbapi/ginplayerhistory/inquire/v1/db/wager', 'GameProviders\CQ9Controller@wager');
Route::get('gbapi/ginplayerhistory/inquire/v1/db/wager', 'GameProviders\CQ9Controller@wager');
Route::get('feedback/', 'GameProviders\CQ9Controller@cq9Feedback');
Route::get('/api/frontend/feedback/init', 'GameProviders\CQ9Controller@cq9FeedbackInit');
Route::post('/api/frontend/feedback/create', 'GameProviders\CQ9Controller@cq9FeedbackCreate');


/**
 * ThePlus Game Provider
 */
Route::post('/tp/signal', 'GameProviders\TPController@userSignal');

/**
 * Pragmatic Play Game Provider
 */
Route::post('/pp/userbet', 'GameProviders\PPController@userbet');
Route::group(['middleware' => 'pp', 'prefix' => 'pp',], function () {
	Route::post('/auth', 'GameProviders\PPController@auth');
	Route::post('/balance', 'GameProviders\PPController@balance');
    Route::post('/bet', 'GameProviders\PPController@bet');
    Route::post('/result', 'GameProviders\PPController@result');
    Route::post('/bonuswin', 'GameProviders\PPController@bonuswin');
    Route::post('/jackpotwin', 'GameProviders\PPController@jackpotwin');
	Route::post('/endround', 'GameProviders\PPController@endround');
    Route::post('/refund', 'GameProviders\PPController@refund');
    Route::post('/promowin', 'GameProviders\PPController@promowin');
});
Route::group(['prefix' => 'gs2c',], function () {
    Route::get('/promo/active', 'GameProviders\PPController@promoactive');
    Route::get('/promo/race/details', 'GameProviders\PPController@promoracedetails');
    Route::get('/promo/race/prizes', 'GameProviders\PPController@promoraceprizes');
    Route::post('/promo/race/winners', 'GameProviders\PPController@promoracewinners');
    Route::post('/promo/race/v2/winners', 'GameProviders\PPController@promoracewinners');
    Route::post('/promo/tournament/player/choice/OPTIN/', 'GameProviders\PPController@promotournamentchoice');
    Route::post('/promo/race/player/choice/OPTIN/', 'GameProviders\PPController@promoracechoice');
    Route::get('/promo/tournament/player/choice/OPTIN/', 'GameProviders\PPController@promotournamentchoice');
    Route::get('/promo/race/player/choice/OPTIN/', 'GameProviders\PPController@promoracechoice');
    Route::get('/promo/race/winners', 'GameProviders\PPController@promoracewinners');
    Route::get('/promo/race/v2/winners', 'GameProviders\PPController@promoracewinners');
    Route::get('/promo/tournament/details', 'GameProviders\PPController@promotournamentdetails');
    Route::get('/promo/tournament/v3/leaderboard', 'GameProviders\PPController@promotournamentleaderboard');
    Route::get('/promo/frb/available', 'GameProviders\PPController@promofrbavailable');  
    Route::get('/announcements/unread', 'GameProviders\PPController@announcementsunread');
    Route::get('/promo/tournament/scores', 'GameProviders\PPController@promotournamentscores');
    Route::get('/minilobby/games.json', 'GameProviders\PPController@minilobby_games_json');
    Route::get('/minilobby/start', 'GameProviders\PPController@minilobby_start');
    Route::get('lastGameHistory.do', 'GameProviders\PPController@ppHistory');
    Route::get('api/history/v2/settings/general', 'GameProviders\PPController@general');
    Route::get('api/history/v2/play-session/last-items', 'GameProviders\PPController@last_items');
    Route::get('api/history/v2/action/children', 'GameProviders\PPController@children');    
    Route::get('session/play-verify/{gamecode}', 'GameProviders\PPController@verify');    
    Route::get('session/verify/{gamecode}', 'GameProviders\KTENController@ppverify');  
});
Route::get('pphistory/{symbol}/{filename}.{hash}.min.css', 'GameProviders\PPController@historymaincss');    
Route::get('pphistory/{symbol}/{filename}.{hash}.min.js', 'GameProviders\PPController@historymainjs');    

Route::post('games/{ppgame}/saveSettings.do', 'GameProviders\PPController@savesettings');


/**
 * Booongo Game Provider
 */
Route::group(['middleware' => 'bng', 'prefix' => 'bng',], function () {
	Route::post('/betman/', 'GameProviders\BNGController@betman');
});

Route::prefix('booongo')->group(function () { 
    Route::post('/process', [
        'as' => 'frontend.booongo.process',
        'uses' => 'GameProviders\BNGController@booongo_process',
    ]);
    Route::post('/desktoplog', [
        'as' => 'frontend.booongo.desktoplog',
        'uses' => 'GameProviders\BNGController@booongo_desktoplog',
    ]);
    Route::post('/mobilelog', [
        'as' => 'frontend.booongo.mobilelog',
        'uses' => 'GameProviders\BNGController@booongo_mobilelog',
    ]);
});

Route::post('op/major/history/api/v1/game/list', [
    'as' => 'frontend.booongo.game_list',
    'uses' => 'GameProviders\BNGController@booongo_game_list',
]);
Route::post('op/major/history/api/v1/transaction/list', [
    'as' => 'frontend.booongo.transaction_list',
    'uses' => 'GameProviders\BNGController@booongo_transaction_list',
]);
Route::post('op/major/history/api/v1/playergame/aggregate', [
    'as' => 'frontend.booongo.aggregate',
    'uses' => 'GameProviders\BNGController@booongo_aggregate',
]);
Route::get('op/major/history/draw/{transaction_id}', [
    'as' => 'frontend.booongo.draw_log',
    'uses' => 'GameProviders\BNGController@booongo_draw_log',
]);

/**
 * Habanero Game Provider
 */
Route::group(['middleware' => 'hbn', 'prefix' => 'hbn',], function () {
	Route::post('/endpoint', 'GameProviders\HBNController@endpoint');
});
Route::post('hbn/history/{requestname}', 'GameProviders\HBNController@history');
Route::get('hbnhistory/history.do', 'GameProviders\HBNController@historyIndex');

/**
 * Play'n Go Game Provider
 */
Route::group(['prefix' => 'png',], function () {
	Route::post('/endpoint/{service}', 'GameProviders\PNGController@endpoint');
});

/**
 * Evolution Game Provider
 */
Route::group(['prefix' => 'evo',], function () {
	Route::post('/rtne/{service}', 'GameProviders\EVOController@endpoint');
    Route::post('/live/{service}', 'GameProviders\EVOController@endpoint');
});

/**
 * ATA Game Provider
 */
Route::group([ 'prefix' => 'ata',], function () {
	Route::post('/api/{service}', 'GameProviders\ATAController@endpoint');
});

/**
 * GameArtCasino Game Provider
 */
Route::group([ 'prefix' => 'gac',], function () {
	Route::get('/checkPlayer/{userid}', 'GameProviders\GACController@checkplayer');
    Route::get('/balance/{userid}', 'GameProviders\GACController@balance');
    Route::post('/placeBet', 'GameProviders\GACController@placebet');
    Route::post('/betResult', 'GameProviders\GACController@betresult');

    //for rendering
    Route::get('/golobby', 'GameProviders\GACController@embedGACgame');
});

/**
 * DreamGaming Game Provider
 */
Route::group([ 'prefix' => 'dg',], function () {
	Route::post('/user/getBalance/{agentName}', 'GameProviders\DGController@getBalance');
    Route::post('/account/transfer/{agentName}', 'GameProviders\DGController@transfer');
    Route::post('/account/checkTransfer/{agentName}', 'GameProviders\DGController@checkTransfer');
    Route::post('/account/inform/{agentName}', 'GameProviders\DGController@inform');
    Route::post('/account/order/{agentName} ', 'GameProviders\DGController@order');
    Route::post('/account/unsettle/{agentName}  ', 'GameProviders\DGController@unsettle');
});

/**
 * SPOGAME Provider
 */
Route::group(['prefix' => 'spogame',], function () {
	Route::get('/balance', 'GameProviders\SPGController@balance');
    Route::post('/changebalance', 'GameProviders\SPGController@changebalance');
});


/**
 * HPPlayCasino  Provider
 */

// Route::post('/hpc/signal', 'GameProviders\HPCController@userSignal');
Route::post('/{provider}/signal', 'GameProviders\ApiController@userSignal');


/**
 * GamePlay Games
 */

Route::post('/REST/GameEngine/Livebetpool', 'GameProviders\GamePlayController@Livebetpool');
Route::post('/REST/GameEngine/GetMemberDrawResult', 'GameProviders\GamePlayController@GetMemberDrawResult');
Route::post('/REST/GameEngine/HistoryBet', 'GameProviders\GamePlayController@HistoryBet');
Route::post('/REST/GameEngine/MultiLimit', 'GameProviders\GamePlayController@MultiLimit');
Route::post('/REST/GameEngine/GameSetting', 'GameProviders\GamePlayController@GameSetting');
Route::post('/REST/GameEngine/DrawResult', 'GameProviders\GamePlayController@DrawResult');
Route::post('/REST/GameEngine/WinLose', 'GameProviders\GamePlayController@WinLose');
Route::get('/REST/GameEngine/OpenBet3', 'GameProviders\GamePlayController@OpenBet3');
Route::get('/REST/GameEngine/ServerTime', 'GameProviders\GamePlayController@ServerTime');
Route::post('/REST/GameEngine/UserInfo', 'GameProviders\GamePlayController@UserInfo');
Route::post('/REST/GameEngine/SpreadBet', 'GameProviders\GamePlayController@SpreadBet');
Route::post('/REST/GameEngine/Trend', 'GameProviders\GamePlayController@Trend');

Route::post('/REST/GameConfig/GetActiveProductsByVendor', 'GameProviders\GamePlayController@GetActiveProductsByVendor');

Route::get('/REST/TrialPromo/GetTrialPromotionInfo', 'GameProviders\GamePlayController@GetTrialPromotionInfo');

//from node server

Route::post('/REST/GameCore/trendInfo', 'GameProviders\GamePlayController@processCurrentTrend');

Route::get('/GamePlay/WinPowerBall', 'GameProviders\GamePlayController@powerball');


//for powerballs

Route::group(['prefix' => '/api/pbgame',], function () {
	Route::post('/userinfo', 'GameParsers\PowerBall\PowerBallController@userInfo');
    Route::post('/history', 'GameParsers\PowerBall\PowerBallController@historyBet');
    Route::post('/round', 'GameParsers\PowerBall\PowerBallController@currentRound');
    Route::post('/recent_rounds', 'GameParsers\PowerBall\PowerBallController@recentRounds');
    Route::post('/placebet', 'GameParsers\PowerBall\PowerBallController@placeBet');
    Route::post('/results', 'GameParsers\PowerBall\PowerBallController@results');
});
