<?php 

namespace VanguardLTE\Http\Middleware
{
    class BNGMiddleware
    {
        public function getIp(){
            foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
                if (array_key_exists($key, $_SERVER) === true){
                    foreach (explode(',', $_SERVER[$key]) as $ip){
                        $ip = trim($ip); // just to be safe
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                            return $ip;
                        }
                    }
                }
            }
            return request()->ip(); // it will return server ip when no client ip found
        }
        
        public function handle($request, \Closure $next)
        {
            /*$bng_ip = config('app.bng_ip');
            if ($this->getIp() != $bng_ip)
            {
                $response = \Response::json(['uid' => '', 'error' => ['code' => "security hash is invalid"]], 503, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }*/
            $securityhash = $request->header('Security-Hash');
            if( !$securityhash ) 
            {
                $response = \Response::json(['uid' => '', 'error' => ['code' => "security hash is emptry"]], 503, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            $calc_hash = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::calcSecurityHash($request->getContent());
            if ($securityhash != $calc_hash)
            {
                $response = \Response::json(['uid' => '', 'error' => ['code' => "security hash is invalid"]], 503, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }

            return $next($request);
        }
    }

}
