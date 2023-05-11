<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class AuthController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('guest', [
                'except' => ['getLogout']
            ]);
            $this->middleware('auth', [
                'only' => ['getLogout']
            ]);
            $this->users = $users;
        }
        public function getLogin(\Illuminate\Http\Request $request)
        {
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            if (!$site)
            {
                return response()->view('system.pages.siteisclosed', [], 200)->header('Content-Type', 'text/html');
            }
            return view('backend.argon.auth.login', compact('site'));
        }
        public function postLogin(\VanguardLTE\Http\Requests\Auth\LoginRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $siteMaintence = env('MAINTENANCE', 0);

            

            $throttles = settings('throttle_enabled');
            if( $throttles && $this->hasTooManyLoginAttempts($request) ) 
            {
                return $this->sendLockoutResponse($request);
            }
            $credentials = $request->getCredentials();
            if( !\Auth::validate($credentials) ) 
            {
                if( $throttles ) 
                {
                    $this->incrementLoginAttempts($request);
                }
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(trans('auth.failed'));
            }
            $user = \Auth::getProvider()->retrieveByCredentials($credentials);

            //check admin id per site
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->get();
            $adminid = [0]; //default admin id
            if (count($site) > 0)
            {
                $adminid = $site->pluck('adminid')->toArray();
            }

            $admin = $user;
            if (!$admin->hasRole(['admin','group']))
            {

                while ($admin !=null && !$admin ->hasRole('comaster'))
                {
                    if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::DELETED)
                    {
                        return redirect()->to(argon_route('argon.auth.login'))->withErrors('삭제된 계정입니다.');
                    }
                    if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::BANNED)
                    {
                        return redirect()->to(argon_route('argon.auth.login'))->withErrors('계정이 임시 차단되었습니다.');
                    }
                    if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::JOIN || $admin->status == \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED)
                    {
                        return redirect()->to(argon_route('argon.auth.login'))->withErrors('가입신청을 처리중입니다.');
                    }
                    if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::REJECTED)
                    {
                        return redirect()->to(argon_route('argon.auth.login'))->withErrors('가입신청이 거부되었습니다.');
                    }
                    
                    $admin = $admin->referral;
                }

                if (!$admin || !in_array($admin->id, $adminid))
                {
                    return redirect()->to(argon_route('argon.auth.login'))->withErrors(trans('auth.failed'));
                }

                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::DELETED)
                {
                    return redirect()->to(argon_route('argon.auth.login'))->withErrors('삭제된 계정입니다.');
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::BANNED)
                {
                    return redirect()->to(argon_route('argon.auth.login'))->withErrors('계정이 임시 차단되었습니다.');
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::JOIN || $admin->status == \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED)
                {
                    return redirect()->to(argon_route('argon.auth.login'))->withErrors('가입신청을 처리중입니다.');
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::REJECTED)
                {
                    return redirect()->to(argon_route('argon.auth.login'))->withErrors('가입신청이 거부되었습니다.');
                }
            }

            if( !$user->hasRole(['admin']) && $siteMaintence==1 ) 
            {
                \Auth::logout();
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(['사이트 점검중입니다']);
            }
            
            if( !$user->hasRole(['admin','group']) && setting('siteisclosed') ) 
            {
                \Auth::logout();
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(trans('app.site_is_turned_off'));
            }
            if( $user->hasRole([
                1, 
                2, 
                3
            ]) && (!$user->shop || $user->shop->is_blocked || $user->shop->pending != 0) ) 
            {
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(trans('app.your_shop_is_blocked'));
            }
            if( $user->isBanned() ) 
            {
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(trans('app.your_account_is_banned'));
            }

            // block Internet Explorer
            $ua = $request->header('User-Agent');
            if (str_contains($ua,'Trident') )
            {
                return redirect()->to(argon_route('argon.auth.login'))->withErrors(['크롬브라우저를 이용하세요']);
            }
            
            \Auth::login($user, settings('remember_me') && $request->get('remember'));
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
                        return redirect()->to(argon_route('argon.auth.login'))->withErrors(['허용되지 않은 기기에서의 접근입니다.']);
                    }
                }
            }
            event(new \VanguardLTE\Events\User\LoggedIn());

            return redirect()->to(argon_route('argon.dashboard'));
        }
        public function getLogout()
        {
            event(new \VanguardLTE\Events\User\LoggedOut());
            \Auth::logout();
            return redirect()->to(argon_route('argon.auth.login'));
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
            return redirect(route(config('app.admurl').'.auth.login'))->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([$this->loginUsername() => $this->getLockoutErrorMessage($seconds)]);
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
    }

}
