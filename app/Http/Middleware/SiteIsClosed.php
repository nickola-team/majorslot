<?php 
namespace VanguardLTE\Http\Middleware
{
    class SiteIsClosed
    {
        public function handle($request, \Closure $next)
        {
            if( auth()->check() && auth()->user()->role_id == 6 ) 
            {
                return $next($request);
            }
            if( setting('siteisclosed') ) 
            {
                return response()->view('system.pages.siteisclosed', [], 200)->header('Content-Type', 'text/html');
            }
            $ip_address = $request->server('HTTP_CF_CONNECTING_IP')??($request->server('X_FORWARDED_FOR')??$request->server('REMOTE_ADDR'));
            $ipblock = \VanguardLTE\IPBlockList::where('ip_address', 'like', '%' . $ip_address . '%')->first();
            if(isset($ipblock)){
                return response()->view('system.pages.ip_block', [], 200)->header('Content-Type', 'text/html');
            }
            return $next($request);
        }
    }

}
