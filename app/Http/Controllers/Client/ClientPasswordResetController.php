<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientPasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('client.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We can\'t find a user with that email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Delete any existing password reset tokens for this user
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Create new password reset token
        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Send password reset email
        Mail::to($user->email)->send(new PasswordReset($token, $request->email));

        toastr()->success('Password reset link sent to your email address.');
        return back();
    }

    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;
        
        // Verify token exists and is valid
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordReset || !Hash::check($token, $passwordReset->token)) {
            toastr()->error('This password reset token is invalid.');
            return redirect()->route('client.password.request');
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();
            toastr()->error('This password reset token has expired.');
            return redirect()->route('client.password.request');
        }

        return view('client.auth.passwords.reset', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            toastr()->error('This password reset token is invalid.');
            return back();
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
            toastr()->error('This password reset token has expired.');
            return redirect()->route('client.password.request');
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        toastr()->success('Your password has been reset successfully.');
        return redirect()->route('client-login');
    }
}
