<?php

namespace App\Http\Middleware;

use Closure;

class OnlyActiveUserMiddleware {

    public function handle($request, Closure $next) {

        if ( auth()->user()->status=='active' ) {
            return $next($request);
        }
        
        return redirect()->route('admin.deactive.user', auth()->user()->id);
    }

}