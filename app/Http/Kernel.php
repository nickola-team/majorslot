<?php 
namespace VanguardLTE\Http
{
    class Kernel extends \Illuminate\Foundation\Http\Kernel
    {
        protected $middleware = [
            'VanguardLTE\Http\Middleware\VerifyInstallation', 
            'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode', 
            'VanguardLTE\Http\Middleware\TrimStrings', 
            'Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull', 
            'VanguardLTE\Http\Middleware\TrustProxies',
            '\Mtownsend\RequestXml\Middleware\XmlRequest',
        ];
        protected $middlewareGroups = [
            'web' => [
                'VanguardLTE\Http\Middleware\EncryptCookies', 
                'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse', 
                'Illuminate\Session\Middleware\StartSession', 
                'Illuminate\View\Middleware\ShareErrorsFromSession', 
                'VanguardLTE\Http\Middleware\VerifyCsrfToken', 
                'Illuminate\Routing\Middleware\SubstituteBindings', 
                'VanguardLTE\Http\Middleware\SelectLanguage',
                '\Mtownsend\RequestXml\Middleware\XmlRequest',
            ], 
            'api' => [
                'VanguardLTE\Http\Middleware\UseApiGuard', 
                'throttle:60,1', 
                'bindings'
            ]
        ];
        protected $routeMiddleware = [
            'auth' => 'VanguardLTE\Http\Middleware\Authenticate', 
            'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth', 
            'guest' => 'VanguardLTE\Http\Middleware\RedirectIfAuthenticated', 
            'registration' => 'VanguardLTE\Http\Middleware\Registration', 
            'session.database' => 'VanguardLTE\Http\Middleware\DatabaseSession', 
            'bindings' => 'Illuminate\Routing\Middleware\SubstituteBindings', 
            'throttle' => 'Illuminate\Routing\Middleware\ThrottleRequests', 
            'cache.headers' => 'Illuminate\Http\Middleware\SetCacheHeaders', 
            'role' => 'jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyRole', 
            'permission' => 'jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyPermission', 
            'level' => 'jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyLevel', 
            'ipcheck' => 'VanguardLTE\Http\Middleware\IpMiddleware', 
            'siteisclosed' => 'VanguardLTE\Http\Middleware\SiteIsClosed', 
            'localization' => 'VanguardLTE\Http\Middleware\SelectLanguage', 
            'shopzero' => 'VanguardLTE\Http\Middleware\ShopZero',
            'cq9' => 'VanguardLTE\Http\Middleware\CQ9Middleware', 
            'pp' => 'VanguardLTE\Http\Middleware\PPMiddleware', 
            'bng' => 'VanguardLTE\Http\Middleware\BNGMiddleware', 
            'hbn' => 'VanguardLTE\Http\Middleware\HBNMiddleware',
            'hpc' => 'VanguardLTE\Http\Middleware\HPCMiddleware',
            'simultaneous' => 'VanguardLTE\Http\Middleware\ThrottleSimultaneousRequests',
            'argonbackend' => '\VanguardLTE\Http\Middleware\ArgonBackend',
            'argonaccessrule' => '\VanguardLTE\Http\Middleware\ArgonAccessRule',
        ];
    }

}
