<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuthMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check() && auth()->user()->hasRole('client')){
            return  $next($request);
        }
        return redirect()->route('frontend.client-login');
    }
}
