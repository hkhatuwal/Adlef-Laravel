@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                    <i class="material-symbols-outlined text-2xl">arrow_back</i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">User Profile</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Detailed information about {{ $user->name }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <!-- Action Buttons Row 1 -->
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('admin.users.commissions', $user) }}" 
                   class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-purple-50 text-purple-700 hover:bg-purple-100 dark:bg-purple-900/50 dark:text-purple-400 dark:hover:bg-purple-900 transition-colors group">
                    <i class="material-symbols-outlined text-base mr-2">percent</i>
                    Commissions
                </a>
                <a href="{{ route('admin.users.deposit-accounts', $user) }}" 
                   class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 transition-colors group">
                    <i class="material-symbols-outlined text-base mr-2">savings</i>
                    Deposit Accounts
                </a>
                <a href="{{ route('admin.users.payment-settings', $user) }}" 
                   class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-orange-50 text-orange-700 hover:bg-orange-100 dark:bg-orange-900/50 dark:text-orange-400 dark:hover:bg-orange-900 transition-colors group">
                    <i class="material-symbols-outlined text-base mr-2">payment</i>
                    Payment Settings
                </a>
            </div>
            
            <!-- Action Buttons Row 2 -->
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('admin.users.accounts', $user) }}" 
                   class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/50 dark:text-indigo-400 dark:hover:bg-indigo-900 transition-colors group">
                    <i class="material-symbols-outlined text-base mr-2">account_balance</i>
                    View Accounts
                    <i class="material-symbols-outlined text-base ml-2 transition-transform group-hover:translate-x-1">arrow_forward</i>
                </a>
                <a href="{{ route('admin.users.assets', $user) }}" 
                   class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/50 dark:text-emerald-400 dark:hover:bg-emerald-900 transition-colors group">
                    <i class="material-symbols-outlined text-base mr-2">account_balance_wallet</i>
                    View Assets
                    <i class="material-symbols-outlined text-base ml-2 transition-transform group-hover:translate-x-1">arrow_forward</i>
                </a>
                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium shadow-sm
                    {{ $user->email_verified_at ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400' }}">
                    <i class="material-symbols-outlined text-base mr-2">{{ $user->email_verified_at ? 'verified' : 'pending' }}</i>
                    {{ $user->email_verified_at ? 'Verified Account' : 'Pending Verification' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="col-span-1 space-y-8">
            <!-- Basic Profile Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="p-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($user->profile && $user->profile->avatar)
                                <img src="{{ asset('storage/' . $user->profile->avatar) }}" 
                                     alt="{{ $user->profile?->getFullName() }}" 
                                     class="h-24 w-24 rounded-2xl object-cover ring-4 ring-slate-50 dark:ring-slate-700">
                            @else
                                <div class="h-24 w-24 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 ring-4 ring-slate-50 dark:ring-slate-700 flex items-center justify-center">
                                    <span class="text-slate-600 dark:text-slate-300 text-4xl font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-8">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $user->profile?->getFullName() ?? $user->name }}
                            </h2>
                            <div class="mt-2 flex flex-col gap-1">
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <i class="material-symbols-outlined text-base mr-1 align-text-bottom">badge</i>
                                    Account #{{ $user->account_number }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <i class="material-symbols-outlined text-base mr-1 align-text-bottom">calendar_today</i>
                                    Joined {{ $user->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">contact_mail</i>
                        Contact Information
                    </h3>
                </div>
                <div class="p-8 space-y-6">
                    @if($user->contactDetails)
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Email Address</label>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-900 dark:text-white font-medium">{{ $user->contactDetails->email }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium 
                                        {{ $user->contactDetails->is_email_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                        <i class="material-symbols-outlined text-base mr-1">{{ $user->contactDetails->is_email_verified ? 'verified' : 'pending' }}</i>
                                        {{ $user->contactDetails->is_email_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Phone Number</label>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-slate-900 dark:text-white font-medium">
                                            ({{ $user->contactDetails->country_code }}) {{ $user->contactDetails->phone }}
                                        </span>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $user->contactDetails->phone_type }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium 
                                        {{ $user->contactDetails->is_phone_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                        <i class="material-symbols-outlined text-base mr-1">{{ $user->contactDetails->is_phone_verified ? 'verified' : 'pending' }}</i>
                                        {{ $user->contactDetails->is_phone_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                                <i class="material-symbols-outlined text-3xl text-slate-400">contact_mail</i>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400">No contact details available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Addresses -->
            @if($user->addresses->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">location_on</i>
                        Addresses
                    </h3>
                </div>
                <div class="p-8">
                    @foreach($user->addresses as $address)
                    <div class="mb-4 last:mb-0 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <p class="text-sm text-slate-900 dark:text-white">
                            {{ $address->address_line1 }}
                            @if($address->address_line2)
                                <br>{{ $address->address_line2 }}
                            @endif
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $address->country }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tax Information -->
            @if($user->profile)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">paid</i>
                        Tax Information
                    </h3>
                </div>
                <div class="p-8">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Hong Kong Tax Resident</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->is_hong_kong_tax_resident ? 'Yes' : 'No' }}
                                </dd>
                            </div>
                            @if($user->profile->tax_identification_number)
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Tax Identification Number</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->tax_identification_number }}
                                </dd>
                            </div>
                            @endif
                            @if($user->profile->tin_not_provided_reason)
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Reason for No TIN</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->tin_not_provided_reason }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Middle Column -->
        <div class="col-span-2 space-y-8">
            <!-- Document Verification -->
            @if($user->profile)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">verified_user</i>
                        Document Verification
                    </h3>
                </div>
                <div class="p-8">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Verification Status</span>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                    {{ $user->profile->document_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                    <i class="material-symbols-outlined text-lg mr-2">
                                        {{ $user->profile->document_verified ? 'verified' : 'pending' }}
                                    </i>
                                    {{ $user->profile->document_verified ? 'Verified' : 'Pending Verification' }}
                                </span>
                                @if($user->profile->document_path)
                                    <form action="{{ route('admin.users.verify-document', $user->profile) }}" method="POST" class="inline">
                                        @csrf
                                        @method($user->profile->document_verified ? 'DELETE' : 'POST')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md
                                                {{ $user->profile->document_verified 
                                                    ? 'bg-red-500 text-white hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800' 
                                                    : 'bg-emerald-500 text-white hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-800' }}">
                                            <i class="material-symbols-outlined text-lg mr-2">
                                                {{ $user->profile->document_verified ? 'cancel' : 'check_circle' }}
                                            </i>
                                            {{ $user->profile->document_verified ? 'Unverify Document' : 'Verify Document' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($user->profile->document_path)
                            <div class="mt-6">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Uploaded Document</span>
                                <div class="mt-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/50">
                                                <i class="material-symbols-outlined text-2xl text-indigo-600 dark:text-indigo-400">description</i>
                                            </div>
                                            <div class="ml-4">
                                                <span class="text-sm font-medium text-slate-900 dark:text-white">Verification Document</span>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Identity Verification</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $user->profile->document_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/50 dark:hover:bg-indigo-900 transition-colors">
                                            <i class="material-symbols-outlined text-lg mr-2">visibility</i>
                                            View Document
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                                    <i class="material-symbols-outlined text-3xl text-slate-400">description_off</i>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400">No document uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Personal Information -->
            @if($user->profile)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">person</i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-8">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                        <dl class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Date of Birth</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->date_of_birth?->format('M d, Y') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Place of Birth</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->place_of_birth }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Gender</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ ucfirst($user->profile->gender) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Marital Status</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ ucfirst($user->profile->marital_status) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Occupation</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->current_occupation }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Annual Income Range</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->annual_income_range }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">account_balance</i>
                        Account Information
                    </h3>
                </div>
                <div class="p-8">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                        <dl class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Account Purpose</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->account_purpose }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Funds Source</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->funds_source }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Wealth Source</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->wealth_source }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Third Party Contributions</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->profile->third_party_contributions ? 'Yes' : 'No' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Business Details -->
            @if($user->businessDetails)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">business_center</i>
                        Business Information
                    </h3>
                </div>
                <div class="p-8">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Registration Number</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->businessDetails->registration_no }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Registration Date</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->businessDetails->registration_date->format('M d, Y') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Country</dt>
                                <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $user->businessDetails->country }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 