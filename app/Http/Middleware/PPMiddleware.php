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
            $calc_hash = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::calcHash($params);
            if ($request->hash != $calc_hash )
            {
                $response = \Response::json(['error' => 5, 'description' => 'hash is incorrect'], 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            return $next($request);
        }
    }

}
