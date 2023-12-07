<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
// dd(auth());
        return $next($request);

        if(auth()->check()) {

            $role = auth()->user()->role;

            if(in_array($role, ['admin', 'super_admin'])) {

                return $next($request);

            }

        }

        abort(403, '管理者権限がありません。');



    }


}
