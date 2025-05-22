<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ClientVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user details are not verified at all, redirect to verification page
        if (!$user->areDetailsVerified() && !Route::currentRouteNamed("client.client-logout", "client.client-registration.verify", "client.client-registration.document-pending")) {
            // Check if document verification is specifically pending but other details are verified
            if (isset($user->contactDetails) && isset($user->profile) && 
                $user->contactDetails->is_email_verified && 
                $user->contactDetails->is_phone_verified && 
                !$user->profile->document_verified) {
                return redirect()->route('client.client-registration.document-pending');
            }
            
            // Otherwise, redirect to the general verification page
            return redirect()->route('client.client-registration.verify');
        }
        
        return $next($request);
    }
}
