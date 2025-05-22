<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();

        return view('client.profile.edit', compact('user', 'profile'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate user data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Validate profile data
        $profileValidator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($profileValidator->fails()) {
            return redirect()->back()->withErrors($profileValidator)->withInput();
        }

        // Get or create the user profile
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);

        // Update profile data
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->middle_name = $request->middle_name;



        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
