<?php 

namespace VanguardLTE\Http\Middleware
{
    class PPMiddleware
    {
        public function handle($request, \Closure $next)
        {
            if (!$request->hash || $request->hash == '')
            {
                $response = \Response::json(['error' => 5, 'description' => 'hash not found'], 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }

            $params = $request->except('hash');
            ksort($params);
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $params, 
                array_keys($params)
            ));
            $calc_hash = md5($str_params . config('app.ppsecretkey'));
            if (false /*$request->hash != $calc_hash */)
            {
                $response = \Response::json(['error' => 5, 'description' => 'hash is incorrect'], 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            return $next($request);
        }
    }

}
