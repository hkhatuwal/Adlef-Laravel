@extends('client._layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <!-- Header Section -->
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">My Accounts</h1>
                <p class="mt-2 text-lg text-gray-600">Manage your bank accounts and crypto wallets in one place.</p>

                <!-- Toggle Switch -->

                <div>
                    <div class="mt-6 flex flex-wrap items-center justify-center space-x-6 card p-4 bg-white rounded-lg w-1/2">
                <span class="text-sm font-semibold peer-checked:text-gray-400 text-purple-600 transition-colors duration-300"
                      id="bank-label">
                    Bank Accounts
                </span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="view-toggle" class="sr-only peer">
                            <div class="w-16 h-8 bg-gray-200 peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full shadow-inner transition-all duration-300 peer-checked:bg-purple-600">
                                <div id="circle"
                                     class=" absolute top-1 left-1 bg-white w-6 h-6 rounded-full shadow-lg transform peer-has-checked:bg-purple-600 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-purple-600 opacity-0 peer-checked:opacity-100 transition-all duration-300"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                        <span class="text-sm font-semibold text-gray-400 peer-checked:text-purple-600 transition-colors duration-300"
                              id="crypto-label">
                    Crypto Wallets
                </span>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mb-10">
                <a href="{{ route('client.account.add') }}"
                   class="inline-flex items-center px-6 py-3 bg-black text-white text-sm font-medium rounded-xl hover:bg-gray-800 transform transition hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Bank Account
                </a>
                <a href="{{ route('client.account.add',["type"=>"crypto_wallet"]) }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transform transition hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Crypto Wallet
                </a>
            </div>


            <div id="bank-accounts-view">
                <!-- Account Sections -->
                <div class="space-y-10">
                    <!-- Own Bank Accounts -->
                    <section class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-100 border-b">
                            <h2 class="text-xl font-bold text-gray-900">Own Bank Accounts</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bank Details
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Account Info
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SWIFT/Sort Code
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($ownAccounts as $account)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-900 flex items-center justify-center">
                                                        <span class="text-white font-semibold">{{ strtoupper(substr($account->bank_name, 0, 2)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $account->bank_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $account->account_holder_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $account->account_number }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">SWIFT: {{ $account->swift }}</div>
                                            @if($account->shortcode)
                                                <div class="text-sm text-gray-500">Sort: {{ $account->shortcode }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $account->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $account->is_verified ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bank accounts</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first bank
                                                account.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Third Party Accounts -->
                    <section class="bg-white rounded-xl shadow-sm overflow-hidden border-indigo-200">
                        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-200">
                            <h2 class="text-xl font-bold text-gray-900">Third Party Accounts</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bank Details
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Account Info
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Entity Details
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($thirdPartyAccounts as $account)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                                        <span class="text-white font-semibold">{{ strtoupper(substr($account->bank_name, 0, 2)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $account->bank_name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        SWIFT: {{ $account->swift }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $account->account_holder_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $account->account_number }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                          <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $account->thirdPartyAccount->isCompany() ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $account->thirdPartyAccount->isCompany() ? 'Company' : 'Individual' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">

                                            @if(isset($account->thirdPartyAccount->company))
                                                <div class="text-sm text-gray-900">{{ $account->thirdPartyAccount->company->company_name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    Reg: {{ $account->thirdPartyAccount->company->address->address_line1 }}</div>
                                            @else
                                                <div class="text-sm text-gray-900">{{ $account->thirdPartyAccount->individual->fullName() }}</div>
                                                <div class="text-sm text-gray-500">
                                                    Reg: {{ $account->thirdPartyAccount->individual->address->address_line1 }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $account->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $account->is_verified ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No third party
                                                accounts</h3>
                                            <p class="mt-1 text-sm text-gray-500">Add third party accounts for your
                                                business partners.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

            <div id="crypto-wallets-view" class="hidden">
                <!-- Crypto Wallets -->
                <section class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
                        <h2 class="text-xl font-bold text-gray-900">Crypto Wallets</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Network
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Wallet Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Wallet Address
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($cryptoWallets as $wallet)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($wallet->currency && $wallet->currency->icon)
                                                <img src="{{ asset('storage/' . $wallet->currency->icon) }}"
                                                     alt="{{ $wallet->currency->name }}" class="h-10 w-10 rounded-full">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold">{{ strtoupper(substr($wallet->alias, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $wallet->currency->name ?? 'Unknown Network' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $wallet->alias }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-mono break-all max-w-md">{{ $wallet->wallet_address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $wallet->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $wallet->is_verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No crypto wallets</h3>
                                        <p class="mt-1 text-sm text-gray-500">Start by adding your first crypto
                                            wallet.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
