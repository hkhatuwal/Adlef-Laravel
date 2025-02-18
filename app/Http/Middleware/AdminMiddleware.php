<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('staff'))) {
            return redirect()->route('admin.login');
        }


        return $next($request);
    }
}
