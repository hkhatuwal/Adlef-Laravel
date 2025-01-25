<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\ContactDetail;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    public function saveRegistrationDetails(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => 'client'
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'alias' => $request->alias,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'annual_income_range' => $request->annual_income,
                'account_purpose' => $request->purpose,
                'funds_source' => $request->source_funds,
                'wealth_source' => $request->wealth_source,
                'is_hong_kong_tax_resident' => $request->has('hk_tax_resident'),
                'agreement_accepted' => $request->has('terms')
            ]);

            // Create contact details
            ContactDetail::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone_country . ' ' . $request->phone_number
            ]);

            // Create address
            Address::create([
                'user_id' => $user->id,
                'address_line1' => $request->street_address,
                'address_line2' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'country' => explode(' ', $request->phone_country)[1] ?? 'Hong Kong' // Using phone country or default to Hong Kong
            ]);

            DB::commit();

            // Redirect to login or dashboard
            return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }
}
