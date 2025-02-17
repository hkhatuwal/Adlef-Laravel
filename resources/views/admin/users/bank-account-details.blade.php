@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.accounts', $user) }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                    <i class="material-symbols-outlined text-2xl">arrow_back</i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Bank Account Details</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Viewing bank account information for {{ $user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Main Account Information -->
        <div class="col-span-2 space-y-6">
            <!-- Account Overview -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">account_balance</i>
                        Account Overview
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Account Holder</label>
                            <p class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $bankAccount->account_holder_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Account Number</label>
                            <p class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $bankAccount->account_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Bank Name</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $bankAccount->bank_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">SWIFT Code</label>
                            <p class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $bankAccount->swift }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Branch Code</label>
                            <p class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $bankAccount->branch_code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Short Code</label>
                            <p class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $bankAccount->shortcode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Party Information -->
            @if($bankAccount->account_type === 'ThirdParty' && $bankAccount->thirdPartyAccount)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">group</i>
                        Third Party Details
                        <span class="ml-3 inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium
                            {{ $bankAccount->thirdPartyAccount->third_party_type === 'Individual' 
                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400' 
                                : 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400' }}">
                            {{ $bankAccount->thirdPartyAccount->third_party_type }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if($bankAccount->thirdPartyAccount->third_party_type === 'Individual')
                        <!-- Individual Details -->
                        @php $individual = $bankAccount->thirdPartyAccount->individual; @endphp
                        @if($individual)
                            <div class="space-y-6">
                                <!-- Personal Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Personal Information</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Full Name</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->fname }} {{ $individual->lname }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Date of Birth</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->dob->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Gender</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst($individual->gender) }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Country of Origin</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->country_of_origin }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Contact Information</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Email</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->email }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Contact Number</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->contact }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Document Information</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">ID Number</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->document_id_number }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Issued Country</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $individual->document_issued_country }}</p>
                                        </div>
                                    </div>
                                    @if($individual->document_url)
                                        <div class="mt-4">
                                            <a href="{{ asset('storage/' . $individual->document_url) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/50 dark:hover:bg-indigo-900 transition-colors">
                                                <i class="material-symbols-outlined text-lg mr-2">visibility</i>
                                                View Document
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Relationship Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Relationship</h3>
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $individual->relationship }}</p>
                                </div>

                                <!-- Address Information -->
                                @if($individual->address)
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Address</h3>
                                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                            <p class="text-sm text-slate-900 dark:text-white">
                                                {{ $individual->address->address_line1 }}
                                                @if($individual->address->address_line2)
                                                    <br>{{ $individual->address->address_line2 }}
                                                @endif
                                                <br>{{ $individual->address->city }}, {{ $individual->address->state }} {{ $individual->address->postal_code }}
                                                <br>{{ $individual->address->country }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    @else
                        <!-- Company Details -->
                        @php $company = $bankAccount->thirdPartyAccount->company; @endphp
                        @if($company)
                            <div class="space-y-6">
                                <!-- Company Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Company Information</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Company Name</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->company_name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Registration Number</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->registration_number }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Registration Date</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->registration_date->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Country</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->country }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Contact Information</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Email</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->email }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Contact Number</label>
                                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $company->contact }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registration Proof -->
                                @if($company->registration_proof)
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Registration Document</h3>
                                        <a href="{{ asset('storage/' . $company->registration_proof) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/50 dark:hover:bg-indigo-900 transition-colors">
                                            <i class="material-symbols-outlined text-lg mr-2">visibility</i>
                                            View Registration Document
                                        </a>
                                    </div>
                                @endif

                                <!-- Relationship Information -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Relationship</h3>
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $company->relationship }}</p>
                                </div>

                                <!-- Address Information -->
                                @if($company->address)
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Registered Address</h3>
                                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                            <p class="text-sm text-slate-900 dark:text-white">
                                                {{ $company->address->address_line1 }}
                                                @if($company->address->address_line2)
                                                    <br>{{ $company->address->address_line2 }}
                                                @endif
                                                <br>{{ $company->address->city }}, {{ $company->address->state }} {{ $company->address->postal_code }}
                                                <br>{{ $company->address->country }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Status and Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">verified</i>
                        Verification Status
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Account Status</span>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                {{ $bankAccount->is_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                <i class="material-symbols-outlined text-lg mr-2">
                                    {{ $bankAccount->is_verified ? 'verified' : 'pending' }}
                                </i>
                                {{ $bankAccount->is_verified ? 'Verified' : 'Pending Verification' }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.verify-bank-account', $bankAccount) }}" method="POST" class="mt-4">
                        @csrf
                        @method($bankAccount->is_verified ? 'DELETE' : 'POST')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md
                                {{ $bankAccount->is_verified 
                                    ? 'bg-red-500 text-white hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800' 
                                    : 'bg-emerald-500 text-white hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-800' }}">
                            <i class="material-symbols-outlined text-lg mr-2">
                                {{ $bankAccount->is_verified ? 'cancel' : 'check_circle' }}
                            </i>
                            {{ $bankAccount->is_verified ? 'Revoke Verification' : 'Verify Account' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Type Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">account_circle</i>
                        Account Type
                    </h2>
                </div>
                <div class="p-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                        {{ $bankAccount->account_type === 'Own' 
                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400' 
                            : 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400' }}">
                        <i class="material-symbols-outlined text-lg mr-2">
                            {{ $bankAccount->account_type === 'Own' ? 'person' : 'group' }}
                        </i>
                        {{ $bankAccount->account_type === 'Own' ? 'Personal Account' : 'Third Party Account' }}
                    </span>
                </div>
            </div>

            <!-- Created At -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Added On</span>
                        <span class="text-sm text-slate-900 dark:text-white">{{ $bankAccount->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 