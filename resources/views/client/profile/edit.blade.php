@extends('client._layouts.app')

@section('page-title', 'Edit Profile')

@section('content')
<div class="w-full bg-white overflow-hidden">
    <div class="bg-gray-900 text-white p-6">
        <h1 class="text-2xl font-medium">Edit Your Profile</h1>
        <p class="text-gray-300 mt-2">Update your personal information, address, contact details, and business information</p>
    </div>

    <div class="p-6 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 mb-6 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 mb-6 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 mb-6 rounded-lg" role="alert">
                <div>
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Two Column Layout for Main Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Account Information Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Account Information
                        </h2>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="name" class="block text-sm text-gray-700 mb-2">Username</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            Change Password
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                <p class="text-xs text-gray-500 mt-1">Leave blank if you don't want to change your password</p>
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                            </svg>
                            Personal Information
                        </h2>

                        <div class="space-y-4">
                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="middle_name" class="block text-sm text-gray-700 mb-2">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $profile->middle_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>

                            <!-- Personal Details Row 1 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="alias" class="block text-sm text-gray-700 mb-2">Alias/Nickname</label>
                                    <input type="text" name="alias" id="alias" value="{{ old('alias', $profile->alias ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm text-gray-700 mb-2">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>

                            <!-- Personal Details Row 2 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="gender" class="block text-sm text-gray-700 mb-2">Gender</label>
                                    <select name="gender" id="gender" class="select2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="marital_status" class="block text-sm text-gray-700 mb-2">Marital Status</label>
                                    <select name="marital_status" id="marital_status" class="select2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="">Select Status</option>
                                        <option value="single" {{ old('marital_status', $profile->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status', $profile->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('marital_status', $profile->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('marital_status', $profile->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Personal Details Row 3 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="place_of_birth" class="block text-sm text-gray-700 mb-2">Place of Birth</label>
                                    <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth', $profile->place_of_birth) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="current_occupation" class="block text-sm text-gray-700 mb-2">Current Occupation</label>
                                    <input type="text" name="current_occupation" id="current_occupation" value="{{ old('current_occupation', $profile->current_occupation) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>

                            <!-- Income -->
                            <div>
                                <label for="annual_income_range" class="block text-sm text-gray-700 mb-2">Annual Income Range</label>
                                <input type="text" name="annual_income_range" id="annual_income_range" value="{{ old('annual_income_range', $profile->annual_income_range) }}" placeholder="e.g., $50,000 - $75,000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <!-- Account Purpose -->
                            <div>
                                <label for="account_purpose" class="block text-sm text-gray-700 mb-2">Account Purpose</label>
                                <select name="account_purpose" id="account_purpose" class="select2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    <option value="">Select Purpose</option>
                                    <option value="Investment" {{ old('account_purpose', $profile->account_purpose) == 'Investment' ? 'selected' : '' }}>Investment</option>
                                    <option value="Trading" {{ old('account_purpose', $profile->account_purpose) == 'Trading' ? 'selected' : '' }}>Trading</option>
                                    <option value="Savings" {{ old('account_purpose', $profile->account_purpose) == 'Savings' ? 'selected' : '' }}>Savings</option>
                                    <option value="Business" {{ old('account_purpose', $profile->account_purpose) == 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Other" {{ old('account_purpose', $profile->account_purpose) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Funds Source -->
                            <div>
                                <label for="funds_source" class="block text-sm text-gray-700 mb-2">Source of Funds</label>
                                <textarea name="funds_source" id="funds_source" rows="3" placeholder="e.g., Salary, Business Income, Investment Returns" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('funds_source', $profile->funds_source) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Separate multiple sources with commas</p>
                            </div>

                            <!-- Wealth Source -->
                            <div>
                                <label for="wealth_source" class="block text-sm text-gray-700 mb-2">Source of Wealth</label>
                                <textarea name="wealth_source" id="wealth_source" rows="3" placeholder="e.g., Employment, Business Ownership, Inheritance" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('wealth_source', $profile->wealth_source) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Separate multiple sources with commas</p>
                            </div>

                            <!-- Anticipated Asset Class -->
                            <div>
                                <label for="anticipated_asset_class" class="block text-sm text-gray-700 mb-2">Anticipated Asset Class</label>
                                <input type="text" name="anticipated_asset_class" id="anticipated_asset_class" value="{{ old('anticipated_asset_class', $profile->anticipated_asset_class) }}" placeholder="e.g., Stocks, Bonds, Real Estate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <!-- Third Party Contributions -->
                            <div>
                                <label for="third_party_contributions" class="block text-sm text-gray-700 mb-2">Third Party Contributions</label>
                                <textarea name="third_party_contributions" id="third_party_contributions" rows="2" placeholder="Details about any third party contributions" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('third_party_contributions', $profile->third_party_contributions) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Address Information Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Address Information
                        </h2>

                        <div class="space-y-4">
                            <!-- Address Lines -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="address_line1" class="block text-sm text-gray-700 mb-2">Address Line 1</label>
                                    <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1', $address->address_line1) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="address_line2" class="block text-sm text-gray-700 mb-2">Address Line 2</label>
                                    <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2', $address->address_line2) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>

                            <!-- Location Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm text-gray-700 mb-2">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $address->city) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="state" class="block text-sm text-gray-700 mb-2">State/Province</label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $address->state) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="postal_code" class="block text-sm text-gray-700 mb-2">Postal Code</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="country" class="block text-sm text-gray-700 mb-2">Country</label>
                                    <select name="country" id="country" class="w-ful select2  px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="">Select Country</option>
                                        @foreach(config('constants.country_code_with_name') as $code=>$countryName)
                                            <option value="{{$countryName}}" @selected(old('country', $address->country) == $countryName)>{{$countryName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Contact Details
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="country_code" class="block text-sm text-gray-700 mb-2">Country Code</label>
                                <select id="country_code" name="country_code" class="w-full select2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    @foreach(config('constants.country_code_with_name') as $code=>$country)
                                        <option value="{{$code}}" @selected(old('country_code', $contactDetail->country_code) == $code)>{{"( ".$code." )    ".$country}}</option>
                                    @endforeach
                                </select>
                                @error('country_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $contactDetail->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                        </div>
                    </div>

                    <!-- Citizenship and Tax Information -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path>
                            </svg>
                            Citizenship and Tax Information
                        </h2>

                        <div class="space-y-4">
                            <!-- Citizenship ID (readonly) -->
                            <div>
                                <label for="citizenship_id" class="block text-sm text-gray-700 mb-2">Citizenship ID</label>
                                <input type="text" name="citizenship_id" id="citizenship_id" value="{{ old('citizenship_id', $profile->citizenship_id) }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                                <p class="text-xs text-gray-500 mt-1">This field is managed by administration</p>
                            </div>

                            <!-- Dual Citizenship -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Dual Citizenship</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="has_dual_citizenship" value="1" {{ old('has_dual_citizenship', $profile->has_dual_citizenship) == '1' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-sm text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="has_dual_citizenship" value="0" {{ old('has_dual_citizenship', $profile->has_dual_citizenship) == '0' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-sm text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Hong Kong Tax Resident -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Hong Kong Tax Resident</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_hong_kong_tax_resident" value="1" {{ old('is_hong_kong_tax_resident', $profile->is_hong_kong_tax_resident) == '1' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-sm text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_hong_kong_tax_resident" value="0" {{ old('is_hong_kong_tax_resident', $profile->is_hong_kong_tax_resident) == '0' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-sm text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Tax Identification Number -->
                            <div>
                                <label for="tax_identification_number" class="block text-sm text-gray-700 mb-2">Tax Identification Number</label>
                                <input type="text" name="tax_identification_number" id="tax_identification_number" value="{{ old('tax_identification_number', $profile->tax_identification_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <!-- TIN Not Provided Reason -->
                            <div>
                                <label for="tin_not_provided_reason" class="block text-sm text-gray-700 mb-2">TIN Not Provided Reason</label>
                                <textarea name="tin_not_provided_reason" id="tin_not_provided_reason" rows="2" placeholder="Reason if TIN is not provided" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('tin_not_provided_reason', $profile->tin_not_provided_reason) }}</textarea>
                            </div>

                            <!-- Secondary Tax Country -->
                            <div>
                                <label for="secondary_tax_country_id" class="block text-sm text-gray-700 mb-2">Secondary Tax Country</label>
                                <select name="secondary_tax_country_id" id="secondary_tax_country_id" class="select2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    <option value="">Select Country</option>
                                    @foreach(config('constants.country_code_with_name') as $code=>$countryName)
                                        <option value="{{$code}}" @selected(old('secondary_tax_country_id', $profile->secondary_tax_country_id) == $code)>{{$countryName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Business Details Section -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"></path>
                                <path d="M9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"></path>
                            </svg>
                            Business Details
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="registration_no" class="block text-sm text-gray-700 mb-2">Registration Number</label>
                                <input type="text" name="registration_no" id="registration_no" value="{{ old('registration_no', $businessDetail->registration_no) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="registration_date" class="block text-sm text-gray-700 mb-2">Registration Date</label>
                                    <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date', $businessDetail->registration_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>

                                <div>
                                    <label for="business_country" class="block text-sm text-gray-700 mb-2">Business Country</label>
                                    <select name="business_country" id="business_country" class="select2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="">Select Country</option>
                                        @foreach(config('constants.country_code_with_name') as $code=>$countryName)
                                            <option value="{{$countryName}}" @selected(old('business_country', $businessDetail->country) == $countryName)>{{$countryName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Information and Agreement Status -->
                    <div class="bg-gray-50 p-5 rounded-lg border">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"></path>
                                <path d="M9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"></path>
                            </svg>
                            Document Information and Agreement Status
                        </h2>

                        <div class="space-y-4">
                            <!-- Current Document Path (readonly) -->
                            @if($profile->document_path)
                            <div>
                                <label for="current_document" class="block text-sm text-gray-700 mb-2">Current Document</label>
                                <div class="flex items-center space-x-3">
                                    <input type="text" value="{{ $profile->document_path }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                                    <a href="{{ asset('storage/' . $profile->document_path) }}" target="_blank" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                            @endif



                            <!-- Document Verification Status (readonly) -->
                            <div>
                                <label for="document_verified" class="block text-sm text-gray-700 mb-2">Document Verification Status</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ $profile->document_verified ? 'Verified' : 'Pending Verification' }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                                    @if($profile->document_verified)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">✓ Verified</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">⏳ Pending</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Document verification is managed by administration</p>
                            </div>

                            <!-- Agreement Accepted Status (readonly) -->
                            <div>
                                <label for="agreement_accepted" class="block text-sm text-gray-700 mb-2">Agreement Status</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ $profile->agreement_accepted ? 'Accepted' : 'Not Accepted' }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                                    @if($profile->agreement_accepted)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">✓ Accepted</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">✗ Not Accepted</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Agreement acceptance status cannot be modified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


@push('post-script')
<script src="{{asset('assets/js/profile/script.js')}}"/>
@endpush


@endsection
