<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class checkUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (!$request->session()->exists('user')) {
          // user value cannot be found in session
          return redirect('/');
      }
      else{
        echo "has session";
      }

      // return $next($request);
    }
}
