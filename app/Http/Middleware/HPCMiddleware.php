<?php 

namespace VanguardLTE\Http\Middleware
{
    class HPCMiddleware
    {
        public function handle($request, \Closure $next)
        {
            $seckey = $request->header('secret-key');
            if( !$seckey ) 
            {
                $response = \Response::json(['status' => 0, 'error' => 'INVALID_ACCESS_TOKEN'], 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            $hpc_key = config('app.hpc_key');
            $keys = explode(':', $hpc_key);
            if (count($keys) > 2 && $seckey == $keys[2])
            {
                return $next($request);
            }

            $query = 'SELECT * FROM w_provider_info WHERE provider="hpc" and config like "%' . $seckey . '%"';
            $hpc_info = \DB::select($query);
            $master_id = -1;
            foreach ($hpc_info as $info)
            {
                $master_id = $info->user_id;
            }
            if( $master_id == -1) 
            {
                $response = \Response::json(['status' => 0, 'error' => 'INVALID_ACCESS_TOKEN'], 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            return $next($request);
        }
    }

}
