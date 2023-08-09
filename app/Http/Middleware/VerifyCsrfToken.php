<?php 
namespace VanguardLTE\Http\Middleware
{
    class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
    {
        protected $except = [
            '/game/*/server', 
            '/coinpayment/ipn'
        ];
        public function handle($request, \Closure $next)
        {
            $response = $next($request);
            if($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse == false){
                $response->header('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  
            }       
            return $response;
        }
    }

}
