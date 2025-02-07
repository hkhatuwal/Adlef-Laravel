<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class ClientLogoutController extends Controller
{
    public function logout()
    {
        auth()->logout();

        return redirect()->route('frontend.home')->with('success', 'Logout Success');
    }
}
