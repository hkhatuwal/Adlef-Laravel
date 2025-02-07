<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientLoginController extends Controller
{
    public function showLoginForm(){
        return view('client.auth.login');
    }

    public function login(Request $request){
        $request->validate( [
            'email' =>'required|email',
            'password' => 'required|min:8'
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            toastr()->success('You are now logged in as '.\auth()->user()->name);
            if (!auth()->user()->areDetailsVerified()){
                return redirect()->route('client.client-registration.verify');
            }
            return redirect()->route('frontend.home');

        }

        return back()->withInput()->withErrors(['message' => 'Invalid email or password']);
    }
}
