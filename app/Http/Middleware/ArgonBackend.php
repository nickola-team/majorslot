<?php 

namespace VanguardLTE\Http\Middleware
{
    class ArgonBackend
    {
        public function handle($request, \Closure $next)
        {
            $slug = $request->segment(1);
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->where('backend',$slug)->get();
            if ($site->count() == 0)
            {
                abort(404);
            }
            return $next($request);
        }
    }
}
