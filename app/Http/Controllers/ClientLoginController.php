<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientLoginController extends Controller
{
    public function showLoginForm(){
        return view('frontend.auth.login');
    }
}
