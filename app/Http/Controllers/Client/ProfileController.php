<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Address;
use App\Models\ContactDetail;
use App\Models\BusinessDetail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the profile edit form
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        $address = $user->addresses->first() ?? new Address();
        $contactDetail = $user->contactDetails ?? new ContactDetail();
        $businessDetail = $user->businessDetails ?? new BusinessDetail();

        return view('client.profile.edit', compact('user', 'profile', 'address', 'contactDetail', 'businessDetail'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Validate all data
        $validator = Validator::make($request->all(), [
            // User data
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,

            // Password change validation
            'current_password' => 'nullable|string|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed|required_with:current_password',
            'new_password_confirmation' => 'nullable|string|required_with:new_password',

            // Profile data
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'current_occupation' => 'nullable|string|max:255',
            'annual_income_range' => 'nullable|string|max:255',
            'account_purpose' => 'nullable|string|max:255',
            'funds_source' => 'nullable|string',
            'wealth_source' => 'nullable|string',
            'anticipated_asset_class' => 'nullable|string|max:255',
            'third_party_contributions' => 'nullable|string',
            'has_dual_citizenship' => 'nullable|in:0,1',
            'is_hong_kong_tax_resident' => 'nullable|in:0,1',
            'tax_identification_number' => 'nullable|string|max:50',
            'tin_not_provided_reason' => 'nullable|string',
            'secondary_tax_country_id' => 'nullable|string|max:10',

            // Address data
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',

            // Contact details
            'phone' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:10',

            // Business details
            'registration_no' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'business_country' => 'nullable|string|max:255',

            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Additional validation for current password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Update password if provided
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }
            
            $user->save();

            // Update or create profile
            $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
            $profile->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'alias' => $request->alias,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'current_occupation' => $request->current_occupation,
                'annual_income_range' => $request->annual_income_range,
                'account_purpose' => $request->account_purpose,
                'funds_source' => $request->funds_source,
                'wealth_source' => $request->wealth_source,
                'anticipated_asset_class' => $request->anticipated_asset_class,
                'third_party_contributions' => $request->third_party_contributions,
                'has_dual_citizenship' => $request->has_dual_citizenship === '1',
                'is_hong_kong_tax_resident' => $request->is_hong_kong_tax_resident === '1',
                'tax_identification_number' => $request->tax_identification_number,
                'tin_not_provided_reason' => $request->tin_not_provided_reason,
                'secondary_tax_country_id' => $request->secondary_tax_country_id,
            ]);

            if (!$profile->exists) {
                $profile->user_id = $user->id;
            }
            $profile->save();

            // Update or create address
            $address = $user->addresses->first() ?? new Address(['user_id' => $user->id]);
            $address->fill([
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
            ]);

            if (!$address->exists) {
                $address->user_id = $user->id;
            }
            $address->save();

            // Update or create contact details
            $contactDetail = $user->contactDetails ?? new ContactDetail(['user_id' => $user->id]);
            $contactDetail->fill([
                'phone' => $request->phone,
                'country_code' => $request->country_code,
                'email' => $request->email,
            ]);

            if (!$contactDetail->exists) {
                $contactDetail->user_id = $user->id;
            }
            $contactDetail->save();

            // Update or create business details
            $businessDetail = $user->businessDetails ?? new BusinessDetail(['user_id' => $user->id]);
            $businessDetail->fill([
                'registration_no' => $request->registration_no,
                'registration_date' => $request->registration_date,
                'country' => $request->business_country,
            ]);

            if (!$businessDetail->exists) {
                $businessDetail->user_id = $user->id;
            }
            $businessDetail->save();

            DB::commit();

            $message = 'Profile updated successfully!';
            if ($request->filled('new_password')) {
                $message = 'Profile and password updated successfully!';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating your profile. Please try again.'. $e->getMessage());
        }
    }
}
