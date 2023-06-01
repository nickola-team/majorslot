<?php 

namespace VanguardLTE\Http\Middleware
{
    class ArgonAccessRule
    {
        public function handle($request, \Closure $next)
        {
            if( auth()->check() ) 
            {
                $ip_address = $request->server('HTTP_CF_CONNECTING_IP')??($request->server('X_FORWARDED_FOR')??$request->server('REMOTE_ADDR'));
                $ipversion = strpos($ip_address, ":") === false ? 4 : 6;
                $user = auth()->user();
                if ($user->accessrule)
                {
                    $allow_ips = explode(',', $user->accessrule->ip_address);
                    if ($ipversion == 6 && $user->accessrule->allow_ipv6 == 1)
                    {
                        return $next($request);
                    }
                    if (in_array($ip_address,$allow_ips))
                    {
                        return $next($request);
                    }
                    else
                    {
                        $response = \Response::json(['error' => '허용되지 않은 접근입니다'], 401, []);
                        $response->header('Content-Type', 'application/json');
                        return $response;
                    }
                }
            }
            return $next($request);
        }
    }
}
