<?php 
namespace VanguardLTE\Http\Middleware
{
    class Authenticate
    {
        protected $auth = null;
        public function __construct(\Illuminate\Contracts\Auth\Guard $auth)
        {
            $this->auth = $auth;
        }
        public function handle($request, \Closure $next)
        {
            // return redirect()->guest('/');
            if( $this->auth->guest() ) 
            {
                if( $request->ajax() || $request->wantsJson() ) 
                {
                    return response('Unauthorized.', 401);
                }
                else if( !$request->is('api*') ) 
                {
                    if( $request->is(config('app.slug') . '*') )
                    {
                        return redirect()->guest(argon_route('argon.auth.login'));
                    }
                    if( $request->is(config('app.admurl') . '*') ) 
                    {
                        return redirect()->guest(route(config('app.admurl') . '.auth.login'));
                    }
                    return redirect()->guest('login'); 
                }
            }
            else if( !$request->is('api*') ) 
            {

                if( $request->is(config('app.slug') . '*') && !$this->auth->user()->hasPermission('access.admin.panel') ) 
                {
                    return redirect()->to('/');
                }
                if( !$request->is(config('app.slug') . '*') && $this->auth->user()->hasPermission('access.admin.panel') ) 
                {
                    return redirect()->to(argon_route('argon.dashboard'));
                } 


                if( $request->is(config('app.admurl') . '*') && !$this->auth->user()->hasPermission('access.admin.panel') ) 
                {
                    return redirect()->to('/');
                }
                if( !$request->is(config('app.admurl') . '*') && $this->auth->user()->hasPermission('access.admin.panel') ) 
                {
                    return redirect()->to('/' . config('app.admurl'));
                } 
            }
            return $next($request);
        }
    }

}
