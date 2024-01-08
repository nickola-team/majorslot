<?php 
namespace VanguardLTE\Http\Middleware
{
    class RedirectIfAuthenticated
    {
        protected $auth = null;
        public function __construct(\Illuminate\Contracts\Auth\Guard $auth)
        {
            $this->auth = $auth;
        }
        public function handle($request, \Closure $next)
        {
            if( $this->auth->check() ) 
            {
                if($request->is(config('app.slug') . '*')){
                    return redirect()->to(argon_route('argon.dashboard'));
                }else{
                    return redirect('/');
                }
            }
            return $next($request);
        }
    }

}
