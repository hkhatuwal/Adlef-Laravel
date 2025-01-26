<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientLogoutController extends Controller
{
    public function logout()
    {
        auth()->logout();

        return redirect()->route('frontend.home')->with('success', 'Logout Success');
    }
}
