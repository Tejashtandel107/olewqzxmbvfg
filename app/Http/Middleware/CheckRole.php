<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Helper;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()->role_id != $role) {
            $home_url = Helper::getUserHomeURL($request->user());
            if(!empty($home_url)){
                return redirect($home_url);
            }
            else {
                abort(403);
            }
        }     
        return $next($request);
        
    }
}
