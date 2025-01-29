@extends('_partials.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @if(request()->has('type'))
            @if(request()->get('type') === 'bank_account' && request()->get('ownership') === 'own')
                @include('frontend.account.components.bank-own')
            @elseif(request()->get('type') === 'bank_account' && request()->get('ownership') === 'third_party')
                @include('frontend.account.components.bank-third-party')
            @elseif(request()->get('type') === 'crypto_wallet')
                @include('frontend.account.components.crypto-wallet')
            @endif
        @else
            <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Add to Whitelist</h1>
                    <button class="text-gray-500 hover:text-gray-700">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">What would you like to whitelist?</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Bank Account Option -->
                        <label class="cursor-pointer">
                            <input type="radio" name="account_type" value="bank_account"
                                   class="hidden account-type-radio" checked required>
                            <div class="border rounded-lg p-4 hover:border-blue-500 transition-colors">
                                <div class="flex justify-center mb-2">
                                    <img src="{{ asset('assets/images/bank.png') }}" alt="Bank Account"
                                         class="w-16 h-16">
                                </div>
                                <p class="text-center font-medium">Bank Account</p>
                            </div>
                        </label>

                        <!-- Crypto Wallet Option -->
                        <label class="cursor-pointer">
                            <input type="radio" name="account_type" value="crypto_wallet"
                                   class="hidden account-type-radio" required>
                            <div class="border rounded-lg p-4 hover:border-blue-500 transition-colors">
                                <div class="flex justify-center mb-2">
                                    <img src="{{ asset('assets/images/crypto.png') }}" alt="Crypto Wallet"
                                         class="w-16 h-16">
                                </div>
                                <p class="text-center font-medium">Crypto Wallet</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="ownership-section" class="mb-6 ">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">OWNERSHIP</h3>

                    <div class="space-y-3">
                        <!-- My Own Account Option -->
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="ownership" value="own" class="mr-3 ownership-radio" checked>
                            <span>My Own Account</span>
                        </label>

                        <!-- Third Party Account Option -->
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="ownership" value="third_party" class="mr-3 ownership-radio">
                            <span>Third Party Account</span>
                        </label>

                        <!-- Third Party Type Options -->
                        <div id="third-party-options" class="pl-6 space-y-3 hidden">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="third_party_type" value="individual" class="mr-3">
                                <span>Individual</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="third_party_type" value="company" class="mr-3">
                                <span>Company</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="button" id="next-button"
                            class="w-full bg-black text-white py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors">
                        Next
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('post-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accountTypeRadios = document.querySelectorAll('.account-type-radio');
            const ownershipSection = document.getElementById('ownership-section');
            const ownershipRadios = ownershipSection.querySelectorAll('input[name="ownership"]');
            const thirdPartyOptions = document.getElementById('third-party-options');
            const thirdPartyTypeRadios = thirdPartyOptions.querySelectorAll('input[name="third_party_type"]');
            const nextButton = document.getElementById('next-button');

            // Handle account type selection
            accountTypeRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'bank_account') {
                        ownershipSection.classList.remove('hidden');
                        ownershipRadios.forEach(radio => radio.required = true);
                    } else {
                        ownershipSection.classList.add('hidden');
                        ownershipRadios.forEach(radio => radio.required = false);
                        thirdPartyOptions.classList.add('hidden');
                        thirdPartyTypeRadios.forEach(radio => radio.required = false);
                    }
                });
            });

            // Handle ownership type selection
            ownershipRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'third_party') {
                        thirdPartyOptions.classList.remove('hidden');
                        thirdPartyTypeRadios.forEach(radio => radio.required = true);
                    } else {
                        thirdPartyOptions.classList.add('hidden');
                        thirdPartyTypeRadios.forEach(radio => radio.required = false);
                    }
                });
            });

            // Handle next button click
            nextButton.addEventListener('click', function () {
                const selectedType = document.querySelector('input[name="account_type"]:checked');
                if (!selectedType) {
                    alert('Please select an account type');
                    return;
                }

                let queryParams = `type=${selectedType.value}`;

                if (selectedType.value === 'bank_account') {
                    const selectedOwnership = document.querySelector('input[name="ownership"]:checked');
                    if (!selectedOwnership) {
                        alert('Please select ownership type');
                        return;
                    }
                    queryParams += `&ownership=${selectedOwnership.value}`;

                    if (selectedOwnership.value === 'third_party') {
                        const selectedThirdPartyType = document.querySelector('input[name="third_party_type"]:checked');
                        if (!selectedThirdPartyType) {
                            alert('Please select third party type');
                            return;
                        }
                        queryParams += `&third_party_type=${selectedThirdPartyType.value}`;
                    }
                }

                window.location.href = `${window.location.pathname}?${queryParams}`;
            });
        });
    </script>
@endsection
