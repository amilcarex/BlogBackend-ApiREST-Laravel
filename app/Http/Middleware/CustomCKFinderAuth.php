<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomCKFinderAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        config(['ckfinder.authentication' => function () {
            if (auth('api')->user()) {
                return true;
            } else {
                return false;
            }
        }]);
        return $next($request);
    }
}
