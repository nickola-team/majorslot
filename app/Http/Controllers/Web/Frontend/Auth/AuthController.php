<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend\Auth
{
    use Log;
    class AuthController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        protected $redirectTo = null;
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('guest', [
                'except' => [
                    'getLogout', 
                    'apiLogin'
                ]
            ]);
            $this->middleware('auth', [
                'only' => ['getLogout']
            ]);
            $this->middleware('registration', [
                'only' => [
                    'getRegister', 
                    'postRegister'
                ]
            ]);
            $this->users = $users;
        }
        public function getBasicTheme()
        {
            $frontend = settings('frontend', 'Default');
            if( \Auth::check() ) 
            {
                $shop = Shop::find(\Auth::user()->shop_id);
                if( $shop ) 
                {
                    $frontend = $shop->frontend;
                }
            }
            return $frontend;
        }
        public function getJoin(\Illuminate\Http\Request $request)
        {
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            $frontend = '';
            if ($site)
            {
                $frontend = $site->frontend;
            }
            return view('frontend.'.$frontend.'.layouts.join');
        }

        public function getLogin()
        {
            $frontend = $this->getBasicTheme();
            $directories = [];
            foreach( glob(resource_path() . '/lang/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            return view('frontend.' . $frontend . '.auth.login', compact('directories'));
        }
        public function postLogin(\VanguardLTE\Http\Requests\Auth\LoginRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $siteMaintence = env('MAINTENANCE', 0);

            if( $siteMaintence==1 ) 
            {
                \Auth::logout();
                return redirect()->to('/')->withErrors('사이트 점검중입니다');
            }

            $throttles = settings('throttle_enabled');
            $to = ($request->has('to') ? '?to=' . $request->get('to') : '');
            if( $throttles && $this->hasTooManyLoginAttempts($request) ) 
            {
                return $this->sendLockoutResponse($request);
            }
            $credentials = $request->getCredentials();
            if( settings('use_email') ) 
            {
                if( filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ) 
                {
                    $credentials = [
                        'email' => $credentials['username'], 
                        'password' => $credentials['password']
                    ];
                }
                else
                {
                    $credentials = [
                        'username' => $credentials['username'], 
                        'password' => $credentials['password']
                    ];
                }
            }
            if( !\Auth::validate($credentials) ) 
            {
                if( $throttles ) 
                {
                    $this->incrementLoginAttempts($request);
                }
                return redirect()->to('login' . $to)->withErrors(trans('auth.failed'));
            }
            $user = \Auth::getProvider()->retrieveByCredentials($credentials);
            if( $user->hasRole([
                1, 
                2, 
                3
            ]) && (!$user->shop || $user->shop->is_blocked) ) 
            {
                return redirect()->to('/' . $to)->withErrors('Your shop is blocked');
            }
            if( settings('use_email') && $user->isUnconfirmed() ) 
            {
                return redirect()->to('login' . $to)->withErrors(trans('app.please_confirm_your_email_first'));
            }
            //check admin id per site
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->get();
            $adminid = [1]; //default admin id
            if (count($site) > 0)
            {
                $adminid = $site->pluck('adminid')->toArray();
            }

            $admin = $user;
            while ($admin !=null && !$admin->isInoutPartner())
            {
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::DELETED)
                {
                    return response()->json(['error' => true, 'msg' => '삭제된 계정입니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::BANNED)
                {
                    return response()->json(['error' => true, 'msg' => '계정이 임시 차단되었습니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::JOIN || $admin->status == \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED)
                {
                    return response()->json(['error' => true, 'msg' => '가입신청을 처리중입니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::REJECTED)
                {
                    return response()->json(['error' => true, 'msg' => '가입신청이 거부되었습니다.']);
                }
                $admin = $admin->referral;
            }

            if (!$admin || !in_array($admin->id, $adminid))
            {
                return response()->json(['error' => true, 'msg' => trans('auth.failed')]);
            }
            if (!$admin->isActive())
            {
                return response()->json(['error' => true, 'msg' => '계정이 임시 차단되었습니다.']);
            }

            if (!$user->isInoutPartner())
            {
                foreach ($site as $web)
                {
                    if ($web->adminid == $admin->id && $web->status == 0)
                    {
                        return response()->json(['error' => true, 'msg' => '현재 점검중입니다']);
                    }
                }
            }

            if( !$user->hasRole('admin') && setting('siteisclosed') ) 
            {
                \Auth::logout();
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off')]);
            }
            if( $user->hasRole([
                1, 
                2, 
                3
            ]) && (!$user->shop || $user->shop->is_blocked || $user->shop->pending != 0) ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_shop_is_blocked')]);
            }
            if( $user->isBanned() ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_account_is_banned')]);
            }
            // block Internet Explorer
            $ua = $request->header('User-Agent');
            if (str_contains($ua,'Trident') )
            {
                return redirect()->to('login' . $to)->withErrors(['크롬브라우저를 이용하세요']);
            }
            if( $request->lang ) 
            {
                $user->update(['language' => $request->lang]);
            }
            $user->update(['language' => 'ko']);
            $sessions = $sessionRepository->getUserSessions($user->id);
            $expiretime = env('EXPIRE_TIME_CLOSE', 600);
            $count = count($sessions);
            if(count($sessions) > 0 ) 
            {
                foreach ($sessions as $s)
                {
                    if ($s->last_activity->diffInSeconds(\Carbon\Carbon::now()) >  $expiretime)
                    {
                        $count--;
                    }
                }
                if ($count > 0){
                    return response()->json(['error' => true, 'msg' => '회원님은 이미 다른 기기에서 로그인되었습니다']);
                }
            }

            $sessionRepository->invalidateAllSessionsForUser($user->id);

            \Auth::login($user, settings('remember_me') && $request->get('remember'));

            
            $api_token = $user->generateCode(36);
            $tryCount = 0;
            $bToken = false;
            do{
                $alreadyUser = \VanguardLTE\User::where('api_token', $api_token)->first();
                if (!$alreadyUser)
                {
                    $bToken = true;
                    break;
                }
                $api_token = $user->generateCode(36);
                $tryCount = $tryCount + 1;
            }
            while ($tryCount < 20);
            if ($bToken){
                $user->update(['api_token' => $api_token]);
                // if ($user->playing_game != null)
                // {
                //     // \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::terminate($user->id);
                //     $user->update(['playing_game' => null]);
                // }
                $user = $user->fresh();
                if ($user->api_token != $api_token)
                {
                    return response()->json(['error' => true, 'msg' => '잠시후 다시 시도해주세요.']);
                }
            }
            else
            {
                return response()->json(['error' => true, 'msg' => '잠시후 다시 시도해주세요.']);
            }

            event(new \VanguardLTE\Events\User\LoggedIn());
            if( settings('reset_authentication') && count($sessionRepository->getUserSessions(\Auth::id())) ) 
            {
                foreach( $sessionRepository->getUserSessions($user->id) as $session ) 
                {
                    if( $session->id != session()->getId() ) 
                    {
                        $sessionRepository->invalidateSession($session->id);
                    }
                }
            }
            return $this->handleUserWasAuthenticated($request, $throttles, $user);
        }
        public function apiLogin($game, $token, $mode)
        {
            if( \Auth::check() ) 
            {
                event(new \VanguardLTE\Events\User\LoggedOut());
                \Auth::logout();
            }
            $us = \VanguardLTE\User::where('api_token', '=', $token)->get();
            if( isset($us[0]->id) ) 
            {
                \Auth::loginUsingId($us[0]->id, true);
                $ref = request()->server('HTTP_REFERER');
                if( $mode == 'desktop' ) 
                {
                    $gameUrl = 'game/' . $game . '?lobby_url=frame';
                }
                else
                {
                    $gameUrl = 'game/' . $game . '?lobby_url=' . $ref;
                }
                return redirect()->to($gameUrl);
            }
            else
            {
                return redirect()->to('');
            }
        }
        protected function handleUserWasAuthenticated(\Illuminate\Http\Request $request, $throttles, $user)
        {
            if( $throttles ) 
            {
                $this->clearLoginAttempts($request);
            }
            if (auth()->user()->hasRole('admin') && config('app.checkadmip'))
            {
                //check ip address
                $ip = $request->server('HTTP_CF_CONNECTING_IP')??$request->server('REMOTE_ADDR');
                if (strpos($ip, ':') === false) // not mobile 
                {
                    $allowedIp = explode(',', config('app.admin_ip'));
                    if (!in_array($ip, $allowedIp))
                    {
                        event(new \VanguardLTE\Events\User\IrLoggedIn());
                        \Auth::logout();
                        return redirect('/backend/login')->withErrors('허용되지 않은 기기에서의 접근입니다.');
                    }
                }

            }
            event(new \VanguardLTE\Events\User\LoggedIn());
            if( $request->has('to') ) 
            {
                return redirect()->to($request->get('to'));
            }
            if( !$user->hasRole('user') ) 
            {
                if (config('app.admurl') == 'slot')
                {
                    return redirect()->route(config('app.admurl').'.dashboard');
                }
                if( !\Auth::user()->hasPermission('dashboard') ) 
                {
                    return redirect()->route(config('app.admurl').'.user.list');
                }
                return redirect()->route(config('app.admurl').'.dashboard');
            }
            return redirect()->intended();
        }
        public function getLogout()
        {
            event(new \VanguardLTE\Events\User\LoggedOut());
            if (\Auth::check()){
                $user = auth()->user();
                $b = $user->withdrawAll('getlogout');                
                $user->update([
                    'api_token' => null
                ]);
            }
            \Auth::logout();
            return redirect('/');
        }
        public function loginUsername()
        {
            return 'username';
        }
        protected function hasTooManyLoginAttempts(\Illuminate\Http\Request $request)
        {
            return app('Illuminate\Cache\RateLimiter')->tooManyAttempts($request->input($this->loginUsername()) . $request->ip(), $this->maxLoginAttempts());
        }
        protected function incrementLoginAttempts(\Illuminate\Http\Request $request)
        {
            app('Illuminate\Cache\RateLimiter')->hit($request->input($this->loginUsername()) . $request->ip(), $this->lockoutTime() / 60);
        }
        protected function retriesLeft(\Illuminate\Http\Request $request)
        {
            $attempts = app('Illuminate\Cache\RateLimiter')->attempts($request->input($this->loginUsername()) . $request->ip());
            return $this->maxLoginAttempts() - $attempts + 1;
        }
        protected function sendLockoutResponse(\Illuminate\Http\Request $request)
        {
            $seconds = app('Illuminate\Cache\RateLimiter')->availableIn($request->input($this->loginUsername()) . $request->ip());
            return redirect('/')->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([$this->loginUsername() => $this->getLockoutErrorMessage($seconds)]);
        }
        protected function getLockoutErrorMessage($seconds)
        {
            return trans('auth.throttle', ['seconds' => $seconds]);
        }
        protected function clearLoginAttempts(\Illuminate\Http\Request $request)
        {
            app('Illuminate\Cache\RateLimiter')->clear($request->input($this->loginUsername()) . $request->ip());
        }
        protected function maxLoginAttempts()
        {
            return settings('throttle_attempts', 5);
        }
        protected function lockoutTime()
        {
            $lockout = (int)settings('throttle_lockout_time');
            if( $lockout <= 1 ) 
            {
                $lockout = 1;
            }
            return 60 * $lockout;
        }
        public function getRegister()
        {
            $frontend = $this->getBasicTheme();
            return view('frontend.' . $frontend . '.auth.register');
        }
        public function postRegister(\VanguardLTE\Http\Requests\Auth\RegisterRequest $request)
        {
            $data = $request->only('email', 'username', 'password');
            $user = $this->users->create(array_merge($data, [
                'role_id' => 1, 
                'status' => (settings('use_email') ? \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED : \VanguardLTE\Support\Enum\UserStatus::ACTIVE)
            ]));
            $role = \jeremykenedy\LaravelRoles\Models\Role::where('name', '=', 'User')->first();
            $user->attachRole($role);
            event(new \VanguardLTE\Events\User\Registered($user));
            $message = (settings('use_email') ? trans('app.account_create_confirm_email') : trans('app.account_created_login'));
            if( !settings('use_email') ) 
            {
                \Auth::login($user, true);
            }
            return redirect()->route('frontend.auth.login')->with('success', $message);
        }
        public function checkUsername($username)
        {
            $generated = false;
            $key = 1;
            $logins = [];
            $generate = $username;
            $tmp = explode(',', settings('bots_login'));
            foreach( $tmp as $item ) 
            {
                $item = trim($item);
                if( $item ) 
                {
                    $logins[] = $item;
                }
            }
            while( !$generated ) 
            {
                $count = \VanguardLTE\User::where('username', $generate)->count();
                if( $count || in_array($generate, $logins) ) 
                {
                    $generate = $username . '_' . $key;
                }
                else
                {
                    $generated = true;
                }
                $key++;
            }
            return $generate;
        }
        public function confirmEmail($token)
        {
            if( $user = $this->users->findByConfirmationToken($token) ) 
            {
                $this->users->update($user->id, [
                    'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                    'confirmation_token' => null
                ]);
                return redirect()->to('/')->withSuccess(trans('app.email_confirmed_can_login'));
            }
            return redirect()->to('/')->withErrors(trans('app.wrong_confirmation_token'));
        }
    }

}
