<?php 

namespace VanguardLTE\Http\Middleware
{
    class HBNMiddleware
    {
        public function handle($request, \Closure $next)
        {
            $data = json_decode($request->getContent(), true);
            $auth = $data['auth'];
            if( !$auth || !isset($auth['passkey']) ) 
            {
                $responseData = \VanguardLTE\Http\Controllers\Web\GameProviders\HBNController::playerResponse();
                $responseData['playerdetailresponse']['status']['message'] = "PassKey is empty";
                $response = \Response::json($responseData, 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }

            $key = config('app.hbn_passkey');
            if( $key != $auth['passkey']) 
            {
                $responseData = \VanguardLTE\Http\Controllers\Web\GameProviders\HBNController::playerResponse();
                $responseData['playerdetailresponse']['status']['message'] = "Incorrect PassKey received";
                $response = \Response::json($responseData, 200, []);
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            return $next($request);
        }
    }

}
