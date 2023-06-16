<?php

namespace VanguardLTE\Providers;

use Carbon\Carbon;
use VanguardLTE\Repositories\Activity\ActivityRepository;
use VanguardLTE\Repositories\Activity\EloquentActivity;
use VanguardLTE\Repositories\Country\CountryRepository;
use VanguardLTE\Repositories\Country\EloquentCountry;
use VanguardLTE\Repositories\Permission\EloquentPermission;
use VanguardLTE\Repositories\Permission\PermissionRepository;
use VanguardLTE\Repositories\Role\EloquentRole;
use VanguardLTE\Repositories\Role\RoleRepository;
use VanguardLTE\Repositories\Session\DbSession;
use VanguardLTE\Repositories\Session\SessionRepository;
use VanguardLTE\Repositories\User\EloquentUser;
use VanguardLTE\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use VanguardLTE\Http\Middleware\ThrottleSimultaneousRequests;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('FORCE_HTTPS') == true)
        {
            URL::forceScheme('https');
            // $this->app['request']->server->set('HTTPS','on');
        }
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => settings('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);

        // Enable pagination
        if (!Collection::hasMacro('paginate')) {

            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage)->values()->all(), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }
        $layout = 'Default';
        $backendurl = 'backend';
        $site = \VanguardLTE\WebSite::where('domain', \Request::root())->first();
        if ($site)
        {
            $layout = $site->backend;
            if (strpos($layout, 'Dark') === 0)
            {
                $backendurl = 'slot';
            }
            
            $backendtheme = ['Dark', 'Default', 'Left', 'Top'];
            if (!in_array($layout, $backendtheme))
            {
                $backendurl = $layout;
            }
        }
        \View::share('layout', $layout);
        \View::share('admurl', $backendurl);
        config(['app.admurl' => $backendurl]);
        config(['app.slug' => $layout]);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(ActivityRepository::class, EloquentActivity::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);
        $this->app->singleton(ThrottleSimultaneousRequests::class);

        if ($this->app->environment('local')) {
            //$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            //$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
