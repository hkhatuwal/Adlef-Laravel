<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $result = Auth::attempt([
            "email" => $request->get('email'),
            "password" => $request->get('password')
        ]);
        if (!$result) {
            return back()->withErrors(['error' => 'Invalid Credentials']);
        }
        return redirect()->route('admin.posts.index');
    }

    public function logout()
    {

        Auth::logout();
        return redirect()->route('admin.login');
    }


}
