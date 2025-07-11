<?php 
namespace VanguardLTE\Http\Middleware
{
    class ShopZero
    {
        public function handle($request, \Closure $next)
        {
            if( auth()->check() ) 
            {
            }
            if( auth()->user()->shop_id == 0 ) 
            {
                if( auth()->user()->hasRole('admin')) 
                {
                    return $next($request);
                }
                else
                {
                    return redirect()->to('/backend/')->withErrors([trans('app.no_permission')]);
                }
            }
            return $next($request);
        }
    }

}
