<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/coinpayment/make' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.make.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coinpayment/ajax/payload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.encrypt.payload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coinpayment/ajax/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.create.transaction',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coinpayment/ipn' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.ipn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.login',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::35BpGiL4jO5cEWTC',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O9fCDXDu4USIA5hZ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Z2KL6lUNA2Yt0sQx',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LczrqhIoKZHvGA1Z',
          ),
          1 => NULL,
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/details/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0z9SoMOFEfi5gW4I',
          ),
          1 => NULL,
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aIWV2n9NgU8q8bSP',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3OitY3S5PTxGekrJ',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/avatar/external' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4oQYVxWiNBQ6ZCtW',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/sessions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RdLGN4Qjyrd81EgX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/return' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bIM5Aqfjxa15gpmw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/games' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wB2EjEOVKmJKpUl3',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/category' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::72RRM0ZV39YlyqIh',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/jackpots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OnCostzabKHLiwTq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/transactions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MhscsW2zhZq7cmMr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats/pay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5bgmPyAZsNn3DFVR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats/game' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QoszedLqebmTHjyc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats/bank' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Yi0Sb4JDkPwBGu9J',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats/shop' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Rt3aQOpvoE9E2miK',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/stats/shift' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3UWsRdzxWurigNox',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shifts/start' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::caJJxzUtnvRiutWX',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shifts/info' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pydeVkPeoGelypxs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shops/currency' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::T0IKcyZWQ6RfVHTF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shops/block' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::unPTTUgfIIpyoiD7',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shops/unblock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qN20jIO76OZsWo8A',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/shops/get' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yiOiuepGAqdRpTBl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/activity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IcpFqFc1Tu2wfY4Z',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IJlrvjvRxCa08G92',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.auth.login.post',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/jpstv.json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.jpstv_json',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subsession' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.subsession',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/activity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.activity',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.balance.post',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/balance/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.balance.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/balance/fail' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.balance.fail',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/details/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.update.details',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/password/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.update.password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/avatar/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.update.avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/avatar/update/external' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.update.avatar-external',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/exchange' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.exchange',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/login-details/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.update.login-details',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/two-factor/enable' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.two-factor.enable',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/two-factor/disable' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.two-factor.disable',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/sessions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.sessions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/returns' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.returns',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/jackpots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.jackpots',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/pincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.pincode',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.search',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/setpage.json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.category.setpage',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/game_result' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game_result',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pay_table' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.pay_table',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getgamelist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.getgamelist',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getgamelink' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.getgamelink',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/change_bank_account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.change_bank_account',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/deposit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.deposit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/withdraw' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.withdraw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/deal_withdraw' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.deal_withdraw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/convert_deal_balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.convert_deal_balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/allow_in_out' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.allow_in_out',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/reject_in_out' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.reject_in_out',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/inoutlist.json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.api.inoutlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.auth.login.post',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.search',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/stat_game' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/game_stat/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game_stat.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/bank_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.bank_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shop_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shift_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shift_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/live' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.live_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/deal_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.deal_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/deal_stat/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.deal_stat.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/start_shift' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.start_shift',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_partner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_partner',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_game' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_game',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_month' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_month',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_shift' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_shift',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_create_shift' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_create_shift',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/adjustment_shift_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.adjustment_shift_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/in_out_request' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.in_out_request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/in_out_manage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.in_out_manage',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/activity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.activity',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/details/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.update.details',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/avatar/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.update.avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/avatar/update/external' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.update.avatar-external',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/login-details/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.update.login-details',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/two-factor/enable' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.two-factor.enable',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/two-factor/disable' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.two-factor.disable',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/sessions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.sessions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/setshop' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.setshop',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/tree' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.tree',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.statistics',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/partner_statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.statistics_partner',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/mileage_stat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.mileage_stat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/profile/balance/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.balance.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/user/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/user/mass' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.massadd',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/game' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/games.json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.list.json',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/game/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/game/update/mass' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.mass',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/gamebanks_add' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.gamebanks_add',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/gamebanks_clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.gamebanks_clear',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/category' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/category/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops/admin/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.admin_create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.admin_store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/pincodes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/pincodes/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/pincodes/mass/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.massadd',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/pincodes/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/happyhours' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/happyhours/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/info' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/info/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/info/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api/generate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.generate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api/json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.json',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/returns' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/returns/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/jpgame' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/jpgame/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/jpgame/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.balance',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/role/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/role/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/permission/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.save',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/permission' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/permission/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/permission/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.general',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/settings/general' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.general.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/settings/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.auth',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.auth.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/generator' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.generator',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.generator.post',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops/block' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.shop_block',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/shops/unblock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.shop_unblock',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/settings/sync' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.settings.sync',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/activity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.activity.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/activity/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.activity.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/backend/api/generateFreespin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.generateFreespin',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/game/bet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6fnhjrCn34Ae84MY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/game/endround' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jhXeakEMP12Y1tJM',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/game/debit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UfqB6TV2JAhTILNZ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/game/credit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TBQz3Hp0QcktAhQz',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/game/refund' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aDZvjGAVMEzfWbeB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cq9/transaction/user/payoff' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eITeKtEhTHV0WWdr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::etz0pmLkFC0R5hX1',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::thbWoVAZCwsxBoDJ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/bet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rGOha9Z25GBBE3zL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/result' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::67xQCP3jSbu3E8UO',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/bonuswin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Z4aUPjICeYYkkAfV',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/jackpotwin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U7bYiUOMLTFxmDff',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/endround' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PntqSaElRWObFea3',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/refund' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Sx1c4gEkBmjTxKFR',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pp/promowin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5cexylc5hC2TmG71',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/c(?|oinpayment/(?|make/([^/]++)(*:39)|ajax/rates/([^/]++)(*:65))|ategories/([^/]++)(?|(*:94)|/([^/]++)(*:110))|q9/(?|transaction/(?|record/([^/]++)(*:155)|balance/([^/]++)(*:179))|player/check/([^/]++)(*:209)))|/api/(?|game(?|s/([^/]++)(*:244)|/([^/]++)/server(*:268))|s(?|hops/balance/([^/]++)(*:302)|essions/([^/]++)(?|(*:329)))|users/([^/]++)(?|(*:356)|/(?|edit(*:372)|a(?|vatar(?|(*:392)|/external(*:409)|(*:417))|ctivity(*:433))|sessions(*:450)|balance/([^/]++)(*:474))|(*:483)))|/launcher/([^/]++)/([^/]++)/([^/]++)(*:529)|/jpstv(?:/([^/]++))?(*:557)|/profile/sessions/([^/]++)/invalidate(*:602)|/setlang/([^/]++)(*:627)|/game/([^/]++)(?|(*:652)|/server(*:667))|/backend/(?|p(?|rofile/(?|sessions/([^/]++)/invalidate(*:730)|balance/setbonus/([^/]++)(*:763))|incodes/([^/]++)/(?|edit(*:796)|update(*:810)|delete(*:824))|ermission/([^/]++)/(?|edit(*:859)|update(*:873)|delete(*:887)))|user/(?|([^/]++)/(?|s(?|tat(*:924)|how(*:935)|essions(?|(*:953)|/([^/]++)/invalidate(*:981)))|profile(*:998)|update/(?|details(*:1023)|login\\-details(*:1046)|avatar(?|(*:1064)|/external(*:1082)))|delete(*:1099)|hard_delete(*:1119)|two\\-factor/(?|enable(*:1149)|disable(*:1165)))|action/([^/]++)(*:1191))|game/(?|([^/]++)(?|/(?|s(?|how(*:1231)|erver(*:1245))|edit(*:1259)|update(*:1274)|delete(*:1289))|(*:1299))|categories(*:1319))|category/([^/]++)/(?|edit(*:1354)|update(*:1369)|delete(*:1384))|shops/([^/]++)/(?|edit(*:1416)|update(*:1431)|delete(*:1446)|hard_delete(*:1466)|action/([^/]++)(*:1490))|happyhours/([^/]++)/(?|edit(*:1527)|update(*:1542)|delete(*:1557))|info/([^/]++)/(?|edit(*:1588)|update(*:1603)|delete(*:1618))|a(?|pi/([^/]++)/(?|edit(*:1651)|update(*:1666)|delete(*:1681))|ctivity/user/([^/]++)/log(*:1716))|r(?|eturns/([^/]++)/(?|edit(*:1753)|update(*:1768)|delete(*:1783))|ole/([^/]++)/(?|edit(*:1813)|update(*:1828)|delete(*:1843)))|jpgame/([^/]++)/(?|edit(*:1877)|update(*:1892))))/?$}sDu',
    ),
    3 => 
    array (
      39 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.make.show',
          ),
          1 => 
          array (
            0 => 'make',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      65 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coinpayment.rates',
          ),
          1 => 
          array (
            0 => 'usd',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      94 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.list.category',
          ),
          1 => 
          array (
            0 => 'category1',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      110 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.list.category_level2',
          ),
          1 => 
          array (
            0 => 'category1',
            1 => 'category2',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      155 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aU3otLTwarNIWkDG',
          ),
          1 => 
          array (
            0 => 'mtcode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      179 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PbQbLLUDA5ro4I2k',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      209 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wfx7DX7lmCnGzOWU',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      244 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g0cJuc6P6BBFxtRf',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      268 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mug2hpYgTwOgwNFX',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      302 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dtPRLIGzVfVePVsL',
          ),
          1 => 
          array (
            0 => 'type',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      329 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eWofjE3ExHXPyKXR',
          ),
          1 => 
          array (
            0 => 'session',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jorEzhHuATDL3FVE',
          ),
          1 => 
          array (
            0 => 'session',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      356 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      372 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      392 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dnSmgAs6AzkytTkG',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      409 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kzwxY2C3k5taf75U',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      417 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::F2XiWtZaVSYBNRXK',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      433 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UHGWTmSKJQtDGVKF',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      450 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VKGVaCZ5eH4wuzOt',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      474 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vXipBpFuAbm9snh3',
          ),
          1 => 
          array (
            0 => 'user',
            1 => 'type',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      483 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      529 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Iw42DCYU9neRXmTE',
          ),
          1 => 
          array (
            0 => 'game',
            1 => 'token',
            2 => 'mode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      557 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.jpstv',
            'id' => NULL,
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      602 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.profile.sessions.invalidate',
          ),
          1 => 
          array (
            0 => 'session',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      627 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.setlang',
          ),
          1 => 
          array (
            0 => 'lang',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      652 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.go',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      667 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'frontend.game.server',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      730 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.profile.sessions.invalidate',
          ),
          1 => 
          array (
            0 => 'session',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      763 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.balance.bonus',
          ),
          1 => 
          array (
            0 => 'userid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      796 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.edit',
          ),
          1 => 
          array (
            0 => 'pincode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      810 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.update',
          ),
          1 => 
          array (
            0 => 'pincode',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      824 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.pincode.delete',
          ),
          1 => 
          array (
            0 => 'pincode',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      859 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.edit',
          ),
          1 => 
          array (
            0 => 'permission',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      873 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.update',
          ),
          1 => 
          array (
            0 => 'permission',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      887 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.permission.delete',
          ),
          1 => 
          array (
            0 => 'permission',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      924 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.stat',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      935 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      953 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.sessions',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      981 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.sessions.invalidate',
          ),
          1 => 
          array (
            0 => 'user',
            1 => 'session',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      998 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1023 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.update.details',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1046 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.update.login-details',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1064 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.update.avatar',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1082 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.update.avatar.external',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1099 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.delete',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1119 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.hard_delete',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1149 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.two-factor.enable',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1165 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.two-factor.disable',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1191 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.user.action',
          ),
          1 => 
          array (
            0 => 'action',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1231 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.show',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1245 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.server',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1259 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.edit',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1274 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.update',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1289 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.delete',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1299 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.go',
          ),
          1 => 
          array (
            0 => 'game',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1319 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.game.categories',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1354 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.edit',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1369 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.update',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1384 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.category.delete',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1416 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.edit',
          ),
          1 => 
          array (
            0 => 'shop',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1431 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.update',
          ),
          1 => 
          array (
            0 => 'shop',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1446 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.delete',
          ),
          1 => 
          array (
            0 => 'shop',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1466 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.hard_delete',
          ),
          1 => 
          array (
            0 => 'shop',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1490 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.shop.action',
          ),
          1 => 
          array (
            0 => 'shop',
            1 => 'action',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1527 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.edit',
          ),
          1 => 
          array (
            0 => 'happyhour',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1542 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.update',
          ),
          1 => 
          array (
            0 => 'happyhour',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1557 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.happyhour.delete',
          ),
          1 => 
          array (
            0 => 'happyhour',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1588 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.edit',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1603 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.update',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1618 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.info.delete',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1651 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.edit',
          ),
          1 => 
          array (
            0 => 'api',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1666 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.update',
          ),
          1 => 
          array (
            0 => 'api',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1681 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.api.delete',
          ),
          1 => 
          array (
            0 => 'api',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1716 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.activity.user',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1753 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.edit',
          ),
          1 => 
          array (
            0 => 'return',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1768 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.update',
          ),
          1 => 
          array (
            0 => 'return',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1783 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.returns.delete',
          ),
          1 => 
          array (
            0 => 'return',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1813 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.edit',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1828 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1843 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.role.delete',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1877 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.edit',
          ),
          1 => 
          array (
            0 => 'jackpot',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1892 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'backend.jpgame.update',
          ),
          1 => 
          array (
            0 => 'jackpot',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'coinpayment.make.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'coinpayment/make',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coinpayment.make.store',
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\MakeTransactionController@store',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\MakeTransactionController@store',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => 'coinpayment/',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'coinpayment.make.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coinpayment/make/{make}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coinpayment.make.show',
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\MakeTransactionController@show',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\MakeTransactionController@show',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => 'coinpayment/',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'coinpayment.rates' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coinpayment/ajax/rates/{usd}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@rates',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@rates',
        'as' => 'coinpayment.rates',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => 'coinpayment/ajax',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'coinpayment.encrypt.payload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'coinpayment/ajax/payload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@encrypt_payload',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@encrypt_payload',
        'as' => 'coinpayment.encrypt.payload',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => 'coinpayment/ajax',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'coinpayment.create.transaction' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'coinpayment/ajax/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@create_transaction',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\AjaxController@create_transaction',
        'as' => 'coinpayment.create.transaction',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => 'coinpayment/ajax',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'coinpayment.ipn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'coinpayment/ipn',
      'action' => 
      array (
        'uses' => 'Hexters\\CoinPayment\\Http\\Controllers\\IPNController@__invoke',
        'controller' => 'Hexters\\CoinPayment\\Http\\Controllers\\IPNController',
        'as' => 'coinpayment.ipn',
        'namespace' => 'Hexters\\CoinPayment\\Http\\Controllers',
        'prefix' => '/coinpayment',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.login' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.login',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@login',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@login',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::35BpGiL4jO5cEWTC' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Auth\\AuthController@logout',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Auth\\AuthController@logout',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::35BpGiL4jO5cEWTC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::O9fCDXDu4USIA5hZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\StatsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\StatsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::O9fCDXDu4USIA5hZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Z2KL6lUNA2Yt0sQx' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Z2KL6lUNA2Yt0sQx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::LczrqhIoKZHvGA1Z' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'api/me/details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::LczrqhIoKZHvGA1Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::0z9SoMOFEfi5gW4I' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'api/me/details/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AuthDetailsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AuthDetailsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0z9SoMOFEfi5gW4I',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::aIWV2n9NgU8q8bSP' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/me/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aIWV2n9NgU8q8bSP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::3OitY3S5PTxGekrJ' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/me/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@destroy',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@destroy',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3OitY3S5PTxGekrJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::4oQYVxWiNBQ6ZCtW' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/me/avatar/external',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@updateExternal',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\AvatarController@updateExternal',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4oQYVxWiNBQ6ZCtW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::RdLGN4Qjyrd81EgX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/sessions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\SessionsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\SessionsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RdLGN4Qjyrd81EgX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::bIM5Aqfjxa15gpmw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/return',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@returns',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Profile\\DetailsController@returns',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::bIM5Aqfjxa15gpmw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::wB2EjEOVKmJKpUl3' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/games',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::wB2EjEOVKmJKpUl3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::g0cJuc6P6BBFxtRf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/games/{game}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@go',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@go',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::g0cJuc6P6BBFxtRf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::mug2hpYgTwOgwNFX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/game/{game}/server',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@server',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Games\\GamesController@server',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mug2hpYgTwOgwNFX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::72RRM0ZV39YlyqIh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/category',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Categories\\CategoriesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Categories\\CategoriesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::72RRM0ZV39YlyqIh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::OnCostzabKHLiwTq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jackpots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Jackpots\\JackpotsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Jackpots\\JackpotsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OnCostzabKHLiwTq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::MhscsW2zhZq7cmMr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/transactions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Transactions\\TransactionsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Transactions\\TransactionsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::MhscsW2zhZq7cmMr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::5bgmPyAZsNn3DFVR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats/pay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@pay',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@pay',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::5bgmPyAZsNn3DFVR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::QoszedLqebmTHjyc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats/game',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@game',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@game',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QoszedLqebmTHjyc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Yi0Sb4JDkPwBGu9J' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats/bank',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@bank',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@bank',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Yi0Sb4JDkPwBGu9J',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Rt3aQOpvoE9E2miK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats/shop',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@shop',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@shop',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Rt3aQOpvoE9E2miK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::3UWsRdzxWurigNox' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/stats/shift',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@shift',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\GameStats\\GameStatsController@shift',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3UWsRdzxWurigNox',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::caJJxzUtnvRiutWX' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/shifts/start',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\OpenShiftController@start_shift',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\OpenShiftController@start_shift',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::caJJxzUtnvRiutWX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::pydeVkPeoGelypxs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/shifts/info',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\OpenShiftController@info',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\OpenShiftController@info',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::pydeVkPeoGelypxs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::T0IKcyZWQ6RfVHTF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/shops/currency',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@currency',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@currency',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::T0IKcyZWQ6RfVHTF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::dtPRLIGzVfVePVsL' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/shops/balance/{type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dtPRLIGzVfVePVsL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::unPTTUgfIIpyoiD7' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/shops/block',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop_block',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop_block',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::unPTTUgfIIpyoiD7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::qN20jIO76OZsWo8A' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/shops/unblock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop_unblock',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop_unblock',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qN20jIO76OZsWo8A',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::yiOiuepGAqdRpTBl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/shops/get',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ShopController@shop',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::yiOiuepGAqdRpTBl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.index',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.show',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@show',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@show',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/users/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'users.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'as' => 'users.destroy',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@destroy',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\UsersController@destroy',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::dnSmgAs6AzkytTkG' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/users/{user}/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dnSmgAs6AzkytTkG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::kzwxY2C3k5taf75U' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/users/{user}/avatar/external',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@updateExternal',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@updateExternal',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kzwxY2C3k5taf75U',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::F2XiWtZaVSYBNRXK' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/users/{user}/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@destroy',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\AvatarController@destroy',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::F2XiWtZaVSYBNRXK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::UHGWTmSKJQtDGVKF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/users/{user}/activity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\ActivityController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\ActivityController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::UHGWTmSKJQtDGVKF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::VKGVaCZ5eH4wuzOt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/users/{user}/sessions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\SessionsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\SessionsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::VKGVaCZ5eH4wuzOt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::vXipBpFuAbm9snh3' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/users/{user}/balance/{type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\BalanceController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\Users\\BalanceController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::vXipBpFuAbm9snh3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::eWofjE3ExHXPyKXR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/sessions/{session}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\SessionsController@show',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\SessionsController@show',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::eWofjE3ExHXPyKXR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::jorEzhHuATDL3FVE' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/sessions/{session}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\SessionsController@destroy',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\SessionsController@destroy',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jorEzhHuATDL3FVE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::IcpFqFc1Tu2wfY4Z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/activity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\ActivityController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\ActivityController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::IcpFqFc1Tu2wfY4Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::IJlrvjvRxCa08G92' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'ipcheck',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Api\\SettingsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Api\\SettingsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Api',
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::IJlrvjvRxCa08G92',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.auth.login',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Iw42DCYU9neRXmTE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'launcher/{game}/{token}/{mode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@apiLogin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@apiLogin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
        'as' => 'generated::Iw42DCYU9neRXmTE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.auth.login.post' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.auth.login.post',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@postLogin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@postLogin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.auth.logout',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@getLogout',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\Auth\\AuthController@getLogout',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.jpstv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'jpstv/{id?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.jpstv',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\PagesController@jpstv',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\PagesController@jpstv',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.jpstv_json' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'jpstv.json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.jpstv_json',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\PagesController@jpstv_json',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\PagesController@jpstv_json',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.subsession' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subsession',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.subsession',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@subsession',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@subsession',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.activity' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/activity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.activity',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@activity',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@activity',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.balance.post' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.balance.post',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@balanceAdd',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@balanceAdd',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.balance.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/balance/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.balance.success',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@success',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@success',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.balance.fail' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/balance/fail',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.balance.fail',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@fail',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@fail',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.update.details' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/details/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.update.details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.update.password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/password/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.update.password',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updatePassword',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updatePassword',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.update.avatar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/avatar/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.update.avatar',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateAvatar',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateAvatar',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.update.avatar-external' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/avatar/update/external',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.update.avatar-external',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateAvatarExternal',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateAvatarExternal',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.exchange' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/exchange',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.exchange',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@exchange',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@exchange',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.update.login-details' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'profile/login-details/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.update.login-details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateLoginDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@updateLoginDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.two-factor.enable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/two-factor/enable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.two-factor.enable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@enableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@enableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.two-factor.disable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/two-factor/disable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.two-factor.disable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@disableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@disableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.sessions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/sessions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.sessions',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@sessions',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@sessions',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.sessions.invalidate' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'profile/sessions/{session}/invalidate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.sessions.invalidate',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@invalidateSession',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@invalidateSession',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.returns' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.returns',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@returns',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@returns',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.jackpots' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/jackpots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.jackpots',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@jackpots',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@jackpots',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.profile.pincode' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/pincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.profile.pincode',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@pincode',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@pincode',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.setlang' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'setlang/{lang}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.setlang',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@setlang',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ProfileController@setlang',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.search' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.search',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@search',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@search',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.list.category' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'categories/{category1}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.list.category',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.list.category_level2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'categories/{category1}/{category2}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.list.category_level2',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.category.setpage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'setpage.json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.category.setpage',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@setpage',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@setpage',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.go' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'game/{game}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.go',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@go',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@go',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game.server' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'game/{game}/server',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game.server',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@server',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@server',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.game_result' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'game_result',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.game_result',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@game_result',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@game_result',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.pay_table' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pay_table',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.pay_table',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@pay_table',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\GamesController@pay_table',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.getgamelist' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getgamelist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.getgamelist',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@getgamelist',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@getgamelist',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.getgamelink' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getgamelink',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.getgamelink',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@getgamelink',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@getgamelink',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.change_bank_account' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/change_bank_account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.change_bank_account',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@changeBankAccount',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@changeBankAccount',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.deposit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/deposit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.deposit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@deposit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@deposit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.withdraw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/withdraw',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.withdraw',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@withdraw',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@withdraw',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.deal_withdraw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/deal_withdraw',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.deal_withdraw',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@withdrawDealMoney',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@withdrawDealMoney',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.convert_deal_balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/convert_deal_balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.convert_deal_balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@convertDealBalance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@convertDealBalance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.allow_in_out' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/allow_in_out',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.allow_in_out',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@allowInOut',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@allowInOut',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.reject_in_out' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/reject_in_out',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.reject_in_out',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@rejectInOut',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@rejectInOut',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'frontend.api.inoutlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inoutlist.json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'siteisclosed',
        ),
        'as' => 'frontend.api.inoutlist',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@inoutList_json',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend\\ApiController@inoutList_json',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Frontend',
        'prefix' => NULL,
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/login',
      'action' => 
      array (
        'middleware' => 'web',
        'as' => 'backend.auth.login',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@getLogin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@getLogin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.auth.login.post' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/login',
      'action' => 
      array (
        'middleware' => 'web',
        'as' => 'backend.auth.login.post',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@postLogin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@postLogin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.auth.logout',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@getLogout',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\Auth\\AuthController@getLogout',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.search' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:full.search',
        ),
        'as' => 'backend.search',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@search',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@search',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.dashboard',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/stat_game',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.game_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@game_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@game_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game_stat.clear' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/game_stat/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game_stat.clear',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@game_stat_clear',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@game_stat_clear',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.bank_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/bank_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.bank',
        ),
        'as' => 'backend.bank_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@bank_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@bank_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shop_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.shop',
        ),
        'as' => 'backend.shop_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@shop_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@shop_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shift_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shift_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.shift',
        ),
        'as' => 'backend.shift_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@shift_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@shift_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.live_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/live',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.live',
        ),
        'as' => 'backend.live_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@live_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@live_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.deal_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/deal_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.shop',
        ),
        'as' => 'backend.deal_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@deal_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@deal_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.deal_stat.clear' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/deal_stat/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.deal_stat.clear',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@deal_stat_clear',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@deal_stat_clear',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.start_shift' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/start_shift',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.start_shift',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@start_shift',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@start_shift',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_partner' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_partner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.adjustment_partner',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_partner',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_partner',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_game' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_game',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.adjustment_game',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_game',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_game',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_month' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_month',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.adjustment_month',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_month',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_month',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_shift' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_shift',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.adjustment_shift',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_shift',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_shift',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_create_shift' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_create_shift',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.game',
        ),
        'as' => 'backend.adjustment_create_shift',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_create_shift',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_create_shift',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.adjustment_shift_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/adjustment_shift_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.adjustment_shift_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_shift_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@adjustment_shift_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.in_out_request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/in_out_request',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.in_out_request',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@in_out_request',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@in_out_request',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.in_out_manage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/in_out_manage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.in_out_manage',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@in_out_manage',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@in_out_manage',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.activity' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/profile/activity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.activity',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@activity',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@activity',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.update.details' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/profile/details/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.update.details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.update.avatar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/profile/avatar/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.update.avatar',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateAvatar',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateAvatar',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.update.avatar-external' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/profile/avatar/update/external',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.update.avatar-external',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateAvatarExternal',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateAvatarExternal',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.update.login-details' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/profile/login-details/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.update.login-details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateLoginDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@updateLoginDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.two-factor.enable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/profile/two-factor/enable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.two-factor.enable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@enableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@enableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.two-factor.disable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/profile/two-factor/disable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.two-factor.disable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@disableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@disableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.sessions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/profile/sessions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.sessions',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@sessions',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@sessions',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.sessions.invalidate' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/profile/sessions/{session}/invalidate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.sessions.invalidate',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@invalidateSession',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@invalidateSession',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.profile.setshop' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'backend/profile/setshop',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.profile.setshop',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@setshop',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ProfileController@setshop',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.manage',
        ),
        'as' => 'backend.user.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.tree' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/tree',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.tree',
        ),
        'as' => 'backend.user.tree',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@tree',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@tree',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.statistics' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.pay',
        ),
        'as' => 'backend.statistics',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@statistics',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@statistics',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.statistics_partner' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/partner_statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.pay',
        ),
        'as' => 'backend.statistics_partner',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@statistics_partner',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@statistics_partner',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.mileage_stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/mileage_stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:stats.pay',
        ),
        'as' => 'backend.mileage_stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@mileage_stat',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\DashboardController@mileage_stat',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.balance.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/profile/balance/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.balance.manage',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateBalance',
        'as' => 'backend.user.balance.update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateBalance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.balance.bonus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/profile/balance/setbonus/{userid}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.balance.manage',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@setBonusSetting',
        'as' => 'backend.user.balance.bonus',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@setBonusSetting',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.add',
        ),
        'as' => 'backend.user.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.add',
        ),
        'as' => 'backend.user.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.stat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user/{user}/stat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.stat',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@statistics',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@statistics',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.massadd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/mass',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.add',
        ),
        'as' => 'backend.user.massadd',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@massadd',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@massadd',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user/{user}/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.show',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@view',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@view',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user/{user}/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.update.details' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/user/{user}/update/details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.update.details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.update.login-details' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/user/{user}/update/login-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.update.login-details',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateLoginDetails',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateLoginDetails',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/user/{user}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.delete',
        ),
        'as' => 'backend.user.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.hard_delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/user/{user}/hard_delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.delete',
        ),
        'as' => 'backend.user.hard_delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@hard_delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@hard_delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.update.avatar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/{user}/update/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.update.avatar',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateAvatar',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateAvatar',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.update.avatar.external' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/{user}/update/avatar/external',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.update.avatar.external',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateAvatarExternal',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@updateAvatarExternal',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.sessions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/user/{user}/sessions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.sessions',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@sessions',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@sessions',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.sessions.invalidate' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/user/{user}/sessions/{session}/invalidate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.sessions.invalidate',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@invalidateSession',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@invalidateSession',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.two-factor.enable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/{user}/two-factor/enable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.two-factor.enable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@enableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@enableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.two-factor.disable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/user/{user}/two-factor/disable',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.two-factor.disable',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@disableTwoFactorAuth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@disableTwoFactorAuth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.user.action' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/user/action/{action}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.user.action',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@action',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\UsersController@action',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/game',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.manage',
        ),
        'as' => 'backend.game.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.list.json' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/games.json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.list.json',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@index_json',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@index_json',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/game/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.add',
        ),
        'as' => 'backend.game.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/game/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.add',
        ),
        'as' => 'backend.game.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/game/{game}/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.show',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@view',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@view',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.go' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/game/{game}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.go',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@go',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@go',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.server' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/game/{game}/server',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.server',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@server',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@server',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/game/{game}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.edit',
        ),
        'as' => 'backend.game.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/game/{game}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/game/{game}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.delete',
        ),
        'as' => 'backend.game.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.categories' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/game/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.categories',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@categories',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@categories',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.mass' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/game/update/mass',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:games.edit',
        ),
        'as' => 'backend.game.mass',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@mass',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@mass',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.gamebanks_add' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/gamebanks_add',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.gamebanks_add',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@gamebanks_add',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@gamebanks_add',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.game.gamebanks_clear' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/gamebanks_clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.game.gamebanks_clear',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@gamebanks_clear',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\GamesController@gamebanks_clear',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/category',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:categories.manage',
        ),
        'as' => 'backend.category.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/category/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:categories.add',
        ),
        'as' => 'backend.category.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/category/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:categories.add',
        ),
        'as' => 'backend.category.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/category/{category}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.category.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/category/{category}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.category.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.category.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/category/{category}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:categories.delete',
        ),
        'as' => 'backend.category.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\CategoriesController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shops',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shops/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/shops/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.admin_create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shops/admin/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.admin_create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@admin_create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@admin_create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.admin_store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/shops/admin/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.admin_store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@admin_store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@admin_store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/shops/{shop}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/shops/{shop}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/shops/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/shops/{shop}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.hard_delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/shops/{shop}/hard_delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.hard_delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@hard_delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@hard_delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.shop.action' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/shops/{shop}/action/{action}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.manage',
        ),
        'as' => 'backend.shop.action',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@action',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ShopsController@action',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/pincodes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:pincodes.manage',
        ),
        'as' => 'backend.pincode.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/pincodes/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:pincodes.add',
        ),
        'as' => 'backend.pincode.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/pincodes/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:pincodes.add',
        ),
        'as' => 'backend.pincode.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.massadd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/pincodes/mass/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:pincodes.add',
        ),
        'as' => 'backend.pincode.massadd',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@massadd',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@massadd',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/pincodes/{pincode}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.pincode.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/pincodes/{pincode}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.pincode.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/pincodes/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.pincode.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.pincode.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/pincodes/{pincode}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:pincodes.delete',
        ),
        'as' => 'backend.pincode.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PincodeController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/happyhours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:happyhours.manage',
        ),
        'as' => 'backend.happyhour.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/happyhours/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:happyhours.add',
        ),
        'as' => 'backend.happyhour.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/happyhours/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:happyhours.add',
        ),
        'as' => 'backend.happyhour.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/happyhours/{happyhour}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.happyhour.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/happyhours/{happyhour}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.happyhour.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.happyhour.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/happyhours/{happyhour}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:happyhours.delete',
        ),
        'as' => 'backend.happyhour.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\HappyHourController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/info',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:helpers.manage',
        ),
        'as' => 'backend.info.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/info/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:helpers.add',
        ),
        'as' => 'backend.info.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/info/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:helpers.add',
        ),
        'as' => 'backend.info.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/info/{info}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.info.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/info/{info}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.info.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/info/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.info.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.info.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/info/{info}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:helpers.delete',
        ),
        'as' => 'backend.info.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\InfoController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/api',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:api.manage',
        ),
        'as' => 'backend.api.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/api/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:api.add',
        ),
        'as' => 'backend.api.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/api/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:api.add',
        ),
        'as' => 'backend.api.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.generate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/api/generate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.generate',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@generate',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@generate',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.json' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/api/json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.json',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@json',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@json',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/api/{api}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/api/{api}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/api/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/api/{api}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:api.delete',
        ),
        'as' => 'backend.api.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ApiController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:returns.manage',
        ),
        'as' => 'backend.returns.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/returns/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:returns.add',
        ),
        'as' => 'backend.returns.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/returns/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:returns.add',
        ),
        'as' => 'backend.returns.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/returns/{return}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.returns.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/returns/{return}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.returns.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.returns.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/returns/{return}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:returns.delete',
        ),
        'as' => 'backend.returns.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ReturnsController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/jpgame',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.list',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/jpgame/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/jpgame/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/jpgame/{jackpot}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/jpgame/{jackpot}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.jpgame.balance' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/jpgame/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.jpgame.balance',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\JPGController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:roles.manage',
        ),
        'as' => 'backend.role.index',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/role/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.role.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/role/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.role.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/role/{role}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.role.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/role/{role}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.role.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.role.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/role/{role}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.role.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\RolesController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/permission/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.permission.save',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@saveRolePermissions',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@saveRolePermissions',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/permission',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:permissions.manage',
        ),
        'as' => 'backend.permission.index',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/permission/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:permissions.add',
        ),
        'as' => 'backend.permission.create',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@create',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@create',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/permission/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:permissions.add',
        ),
        'as' => 'backend.permission.store',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@store',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@store',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/permission/{permission}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.permission.edit',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@edit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@edit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/permission/{permission}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.permission.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.permission.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/permission/{permission}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.permission.delete',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@delete',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\PermissionsController@delete',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.general' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.general',
        ),
        'as' => 'backend.settings.general',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@general',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@general',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.general.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/settings/general',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.general',
        ),
        'as' => 'backend.settings.general.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.auth' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/settings/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.auth',
        ),
        'as' => 'backend.settings.auth',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@auth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@auth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.auth.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/settings/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.auth',
        ),
        'as' => 'backend.settings.auth.update',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@update',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@update',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.generator' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/generator',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.generator',
        ),
        'as' => 'backend.settings.generator',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@generator',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@generator',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.generator.post' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/generator',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:settings.generator',
        ),
        'as' => 'backend.settings.generator.post',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@generator',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@generator',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.shop_block' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/shops/block',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.block',
        ),
        'as' => 'backend.settings.shop_block',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@shop_block',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@shop_block',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.shop_unblock' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/shops/unblock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:shops.unblock',
        ),
        'as' => 'backend.settings.shop_unblock',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@shop_unblock',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@shop_unblock',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.settings.sync' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'backend/settings/sync',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.settings.sync',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@sync',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\SettingsController@sync',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.activity.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/activity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:users.activity',
        ),
        'as' => 'backend.activity.index',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@index',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@index',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.activity.user' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'backend/activity/user/{user}/log',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.activity.user',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@userActivity',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@userActivity',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.activity.clear' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'backend/activity/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.activity.clear',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@clear',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\ActivityController@clear',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'backend.api.generateFreespin' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'backend/api/generateFreespin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'as' => 'backend.api.generateFreespin',
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\EightFortuneSpinGeneraterController@generateFreespin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend\\EightFortuneSpinGeneraterController@generateFreespin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web\\Backend',
        'prefix' => '/backend',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::6fnhjrCn34Ae84MY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/game/bet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@bet',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@bet',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::6fnhjrCn34Ae84MY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::jhXeakEMP12Y1tJM' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/game/endround',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@endround',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@endround',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::jhXeakEMP12Y1tJM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::UfqB6TV2JAhTILNZ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/game/debit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@debit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@debit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::UfqB6TV2JAhTILNZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::TBQz3Hp0QcktAhQz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/game/credit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@credit',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@credit',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::TBQz3Hp0QcktAhQz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::aDZvjGAVMEzfWbeB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/game/refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@refund',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@refund',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::aDZvjGAVMEzfWbeB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::eITeKtEhTHV0WWdr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cq9/transaction/user/payoff',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@payoff',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@payoff',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::eITeKtEhTHV0WWdr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::aU3otLTwarNIWkDG' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cq9/transaction/record/{mtcode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@record',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@record',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::aU3otLTwarNIWkDG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::PbQbLLUDA5ro4I2k' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cq9/transaction/balance/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::PbQbLLUDA5ro4I2k',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::wfx7DX7lmCnGzOWU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cq9/player/check/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'cq9',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@checkplayer',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\CQ9Controller@checkplayer',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/cq9',
        'where' => 
        array (
        ),
        'as' => 'generated::wfx7DX7lmCnGzOWU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::etz0pmLkFC0R5hX1' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@auth',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@auth',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::etz0pmLkFC0R5hX1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::thbWoVAZCwsxBoDJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@balance',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@balance',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::thbWoVAZCwsxBoDJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::rGOha9Z25GBBE3zL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/bet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@bet',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@bet',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::rGOha9Z25GBBE3zL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::67xQCP3jSbu3E8UO' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/result',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@result',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@result',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::67xQCP3jSbu3E8UO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Z4aUPjICeYYkkAfV' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/bonuswin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@bonuswin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@bonuswin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::Z4aUPjICeYYkkAfV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::U7bYiUOMLTFxmDff' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/jackpotwin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@jackpotwin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@jackpotwin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::U7bYiUOMLTFxmDff',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::PntqSaElRWObFea3' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/endround',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@endround',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@endround',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::PntqSaElRWObFea3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::Sx1c4gEkBmjTxKFR' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@refund',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@refund',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::Sx1c4gEkBmjTxKFR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
    'generated::5cexylc5hC2TmG71' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pp/promowin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'pp',
        ),
        'uses' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@promowin',
        'controller' => 'VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\PPController@promowin',
        'namespace' => 'VanguardLTE\\Http\\Controllers\\Web',
        'prefix' => '/pp',
        'where' => 
        array (
        ),
        'as' => 'generated::5cexylc5hC2TmG71',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
    ),
  ),
)
);
