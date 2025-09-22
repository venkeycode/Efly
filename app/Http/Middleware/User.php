<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User
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
        if (isset(Auth::user()->id)) {
            // if(Auth::user()->mobile_verified =='No'){

            //     return redirect(route('otpGenerate'));
            // }

            return $next($request);
      }
      else{
        return redirect(url('/login'));
      }
    }
}
