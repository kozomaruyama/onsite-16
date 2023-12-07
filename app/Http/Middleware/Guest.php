<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Guest as Middleware;

class Guest  extends Middleware
{
    protected function redirectTo($request)
    {
dd($request);
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

}
