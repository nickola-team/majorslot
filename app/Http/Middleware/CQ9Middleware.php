<?php 

namespace VanguardLTE\Http\Middleware
{
    class CQ9Middleware
    {
        public function handle($request, \Closure $next)
        {
            $wtoken = $request->header('wtoken');
            if( !$wtoken ) 
            {
                $response = \Response::json(['error' => 'wtoken is empty or not exist'], 401, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            $key = config('app.cq9wtoken');
            if( $key != $wtoken) 
            {
                $response = \Response::json(['error' => 'wtoken is invalid'], 401, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            return $next($request);
        }
    }

}
