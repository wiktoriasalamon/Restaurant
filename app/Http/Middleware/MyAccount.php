<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MyAccount
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if(Auth::user()->email!==$request->route('user')->email){
           abort(403, 'Access denied');
        }
        return $next($request);
    }

}