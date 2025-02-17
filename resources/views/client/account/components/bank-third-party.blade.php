<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add Third Party Account</h1>
        <a href="{{route('client.account.add')}}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <div class="flex gap-8">
        <!-- Left Side - Steps -->
        <div class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl  p-6 card sticky top-4">
                <div class="space-y-6">
                    <div class="flex items-center space-x-3" id="step-1-indicator">
                        <div
                            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center border-2 border-black">
                            <span class="text-gray-700 font-bold indicator-number ">1</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Bank Info</span>
                    </div>
                    <div class="flex items-center space-x-3 opacity-50" id="step-2-indicator">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-700 font-bold indicator-number">2</span>
                        </div>
                        <span
                            class="text-sm font-medium text-gray-700">{{request()->get('third_party_type')=="company"?"Company Details":"Personal Info"}}</span>
                    </div>
                    <div class="flex items-center space-x-3 opacity-50" id="step-3-indicator">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-700 font-bold indicator-number">3</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Address</span>
                    </div>
                    <div class="flex items-center space-x-3 opacity-50" id="step-4-indicator">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-700 font-bold indicator-number">4</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1">
            <form action="{{route('client.account.add.third-party')}}" method="POST">
                @csrf
                <input type="hidden" name="third_party_type" value="{{ request()->get('third_party_type') }}">
                <!-- Step 1: Bank Info -->
                <div id="step-1" class="bg-white rounded-xl card p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Bank Information</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Account Holder Name</label>
                            <input type="text" name="account_holder" id="account_holder" required>
                            <p class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" required>
                            <p class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Account Number</label>
                            <input type="number" name="account_number" id="account_number" required>
                            <p class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">SWIFT Code</label>
                            <input type="text" name="swift_code" id="swift_code" required>
                            <p class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Personal Info -->
                <div id="step-2" class="bg-white rounded-xl card p-6 mb-6 hidden">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">{{request()->get('third_party_type')=="company"?"Company Details":"Personal Info"}}</h2>
                    <div class="space-y-6">
                        @if(request()->get('third_party_type') === 'individual')
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">First Name</label>
                                        <input type="text" name="first_name" id="first_name" required>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" required>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" required>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Gender</label>
                                        <select name="gender" id="gender" class="select2" required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Country of
                                            Birth/Origin</label>
                                        <select name="country_of_birth" id="country_of_birth" class="select2" required>
                                            <option >Select Country</option>
                                            @foreach(config('constants.country_code_with_name') as $code=>$country)
                                                <option
                                                    value="{{$country}}" @selected(old('phone_country') == $code)>{{$country}}</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Counterparty
                                            Relationship</label>
                                        <select name="counterparty_relationship" id="counterparty_relationship" class="select2" required>
                                            <option value="">Select Relationship</option>
                                            @foreach(config('constants.relationship') as $relationship)
                                                <option value="{{$relationship}}">{{$relationship}}</option>

                                            @endforeach

                                        </select>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Document
                                            Number</label>
                                        <input type="text" name="document_number" id="document_number" required>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Document
                                            Country</label>
                                        <select name="document_country" id="document_country" class="select2" required>
                                            <option value="">Select Country</option>
                                            @foreach(config('constants.country_code_with_name') as $code=>$country)
                                                <option
                                                    value="{{$country}}" @selected(old('phone_country') == $code)>{{$country}}</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Id Proof</label>
                                    @include('_components.file_picker',['id'=>'id_proof'])
                                </div>
                            </div>
                        @else
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" required>
                                    <p class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Registration
                                        Country</label>
                                    <select name="registration_country" id="registration_country" class="select2" required>
                                        <option value="">Select Country</option>
                                        <option value="US">United States</option>
                                        <option value="UK">United Kingdom</option>
                                    </select>
                                    <p class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Registration Date</label>
                                    <input type="date" name="registration_date" id="registration_date" required>
                                    <p class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Registration
                                        Number</label>
                                    <input type="text" name="registration_number" id="registration_number" required>
                                    <p class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Company Registration
                                        Proof</label>
                                    @include('_components.file_picker',['id'=>'company_document_proof'])
                                </div>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contact</label>
                            <div class="flex gap-4 flex-row w-full">
                                <div class="w-3/6">
                                    <select name="country_code" id="country_code" class="select2 w-full" required>
                                        <option value="">Country Code</option>
                                        @foreach(config('constants.country_code_with_name') as $code=>$country)
                                            <option
                                                value="{{$code}}" @selected(old('phone_country') == $code)>({{$code}} ) {{$country}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full">
                                    <input type="tel" name="phone" id="phone" class="w-full" required>
                                    <p class="mt-1 text-sm text-red-600 block w-full"></p>

                                </div>
                            </div>

                        </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" required>
                                <p class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>



                    </div>
                </div>

                <!-- Step 3: Address -->
                <div id="step-3" class="bg-white rounded-xl card p-6 mb-6 hidden">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Address Information</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Street Address</label>
                            <input type="text" name="street_address" id="street_address" required>
                            <p class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                                <input type="text" name="city" id="city" required>
                                <p class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">State</label>
                                <input type="text" name="state" id="state" required>
                                <p class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="country">Country</label>
                                <select name="country" id="country" class="select2" required>
                                    <option value="">Select Country</option>
                                    @foreach(config('constants.country_code_with_name') as $code=>$country)
                                        <option
                                            value="{{$country}}" @selected(old('phone_country') == $code)>{{$country}}</option>

                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" required>
                                <p class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review -->
                <div id="step-4" class="bg-white rounded-xl card p-6 mb-6 hidden">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Review Information</h2>
                    <div class="space-y-8">
                        <!-- Bank Info Review -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Bank Information</h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Account Holder Name</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-account_holder"></p>
                                            <button type="button" onclick="copyToClipboard('review-account_holder')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Bank Name</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-bank-name"></p>
                                            <button type="button" onclick="copyToClipboard('review-bank-name')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Account Number</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-account-number"></p>
                                            <button type="button" onclick="copyToClipboard('review-account-number')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">SWIFT Code</p>
                                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                        <p class=" text-lg text-gray-800" id="review-swift-code"></p>
                                        <button type="button" onclick="copyToClipboard('review-swift-code')"
                                                class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Info Review -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Personal Information</h3>
                            <div class="space-y-4">
                                @if(request()->get('third_party_type') === 'individual')
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">First Name</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-first_name"></p>
                                                <button type="button" onclick="copyToClipboard('review-first_name')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Last Name</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-last_name"></p>
                                                <button type="button" onclick="copyToClipboard('review-last_name')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Date of Birth</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-date_of_birth"></p>
                                                <button type="button" onclick="copyToClipboard('review-date_of_birth')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Gender</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-gender"></p>
                                                <button type="button" onclick="copyToClipboard('review-gender')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Country of
                                                Birth/Origin</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-country_of_birth"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-country_of_birth')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Counterparty
                                                Relationship</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800"
                                                   id="review-counterparty_relationship"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-counterparty_relationship')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Document Number</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-document_number"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-document_number')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Document Country</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-document_country"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-document_country')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Document File</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-document_proof"></p>
                                                <button type="button" onclick="copyToClipboard('review-document_proof')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Company Name</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-company-name"></p>
                                                <button type="button" onclick="copyToClipboard('review-company-name')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Registration Details</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800" id="review-registration-details"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-registration-details')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 mb-2">Relationship</p>
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                                <p class=" text-lg text-gray-800"
                                                   id="review-counterparty-relationship"></p>
                                                <button type="button"
                                                        onclick="copyToClipboard('review-counterparty-relationship')"
                                                        class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Document File</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-company_document_proof"></p>
                                            <button type="button"
                                                    onclick="copyToClipboard('review-company_document_proof')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Phone Number</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-phone"></p>
                                            <button type="button" onclick="copyToClipboard('review-phone')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-2">Email Address</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                            <p class=" text-lg text-gray-800" id="review-email"></p>
                                            <button type="button" onclick="copyToClipboard('review-email')"
                                                    class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Address Review -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Address Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">Complete Address</p>
                                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                        <p class=" text-lg text-gray-800" id="review-address"></p>
                                        <button type="button" onclick="copyToClipboard('review-address')"
                                                class="text-gray-600 hover:text-gray-800 transition-colors copy-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-4">
                    <button type="button" id="prev-btn"
                            class="flex-1 bg-gray-100 text-gray-700 py-4 px-4 rounded-lg hover:bg-gray-200 transition-colors font-bold text-lg hidden">
                        Previous
                    </button>
                    <button type="button" id="next-btn"
                            class="flex-1 bg-black text-white py-4 px-4 rounded-lg hover:bg-gray-800 transition-colors font-bold text-lg shadow-lg shadow-gray-200">
                        Next
                    </button>
                    <button type="submit" id="submit-btn"
                            class="flex-1 bg-black text-white py-4 px-4 rounded-lg hover:bg-gray-800 transition-colors font-bold text-lg shadow-lg shadow-gray-200 hidden">
                        <span class="inline-flex items-center">
                            <span>Submit</span>
                            <svg class="w-5 h-5 ml-2 hidden animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('post-script')
    <script>

        $(document).ready(function () {

            let currentStep = 1;
            const totalSteps = 4;

            const stepElements = {
                1: $('#step-1'),
                2: $('#step-2'),
                3: $('#step-3'),
                4: $('#step-4')
            };

            function showValidationError(input, message) {
                const errorElement = $(input).next();
                errorElement.text(message);
                errorElement.removeClass('hidden');
                $(input).addClass('border-red-500');
            }

            function clearValidationError(input) {
                const errorElement = $(input).next();
                errorElement.addClass('hidden');
                $(input).removeClass('border-red-500');
            }

            function validateCurrentStep() {
                const currentStepElement = stepElements[currentStep];
                const inputs = currentStepElement.find('input, select');
                let isValid = true;
                inputs.each(function () {
                    clearValidationError(this);
                    if ($(this).prop('required') && !$(this).val().trim()) {
                        showValidationError(this, `${$(this).prev().text()} is required`);
                        isValid = false;
                    } else if ($(this).attr('type') === 'email' && $(this).val().trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test($(this).val().trim())) {
                            showValidationError(this, 'Please enter a valid email address');
                            isValid = false;
                        }

                    } else if ($(this).attr('type') === 'tel' && $(this).val().trim()) {
                        const phoneRegex = /^\+?[\d\s-]{10,}$/;
                        if (!phoneRegex.test($(this).val().trim())) {
                            console.log(this)
                            showValidationError(this, 'Please enter a valid phone number');
                            isValid = false;

                        }
                    }
                });

                return isValid;
            }

            function updateStepIndicators() {
                for (let i = 1; i <= totalSteps; i++) {
                    const indicator = $(`#step-${i}-indicator`);
                    if (i === currentStep) {
                        indicator.removeClass('opacity-50');
                        indicator.find('.w-8').addClass('border-2 border-black text-gray-800');
                    } else if (i < currentStep) {
                        indicator.removeClass('opacity-50');
                        indicator.find('.w-8').addClass('bg-gray-800 text-white');
                        indicator.find('.indicator-number').addClass('text-white');
                    } else {
                        indicator.addClass('opacity-50');
                        indicator.find('.w-8').removeClass('bg-gray-800 text-white border-2 border-black');
                        indicator.find('.indicator-number').removeClass('text-white');
                        indicator.find('.indicator-number').addClass('text-gray-800');

                    }
                }
            }

            function showStep(step) {
                $.each(stepElements, function (_, element) {
                    element.addClass('hidden');
                });

                stepElements[step].removeClass('hidden');

                $('#prev-btn').toggleClass('hidden', step === 1);
                $('#next-btn').toggleClass('hidden', step === totalSteps);
                $('#submit-btn').toggleClass('hidden', step !== totalSteps);

                updateStepIndicators();
            }

            function updateReviewSection() {
                // Bank Info
                $('#review-account_holder').text($('#account_holder').val());
                $('#review-bank-name').text($('#bank_name').val());
                $('#review-account-number').text($('#account_number').val());
                $('#review-swift-code').text($('#swift_code').val());

                // Personal/Company Info
                const thirdPartyType = $('input[name="third_party_type"]').val();
                $('#review-email').text($('#email').val());
                $('#review-phone').text($('#country_code option:selected').text() + ' ' + $('#phone').val());

                if (thirdPartyType === 'individual') {
                    $('#review-first_name').text($('#first_name').val());
                    $('#review-last_name').text($('#last_name').val());
                    $('#review-date_of_birth').text($('#date_of_birth').val());
                    $('#review-gender').text($('#gender option:selected').text());
                    $('#review-country_of_birth').text($('#country_of_birth option:selected').text());
                    $('#review-counterparty_relationship').text($('#counterparty_relationship option:selected').text());
                    $('#review-document_number').text($('#document_number').val());
                    $('#review-document_country').text($('#document_country option:selected').text());
                    $('#review-document_proof').text($('#id_proof')[0].files[0].name);
                } else {
                    // Company Information
                    $('#review-company-name').text($('#company_name').val());
                    // Registration Details
                    const registrationDetails = [
                        $('#registration_country option:selected').text(),
                        'Reg. Date: ' + $('#registration_date').val(),
                        'Reg. Number: ' + $('#registration_number').val()
                    ].filter(Boolean).join(' | ');
                    $('#review-registration-details').text(registrationDetails);

                    $('#review-company_document_proof').text($('#company_document_proof')[0].files[0].name);


                    // Counterparty Relationship
                    $('#review-counterparty-relationship').text($('#counterparty_relationship option:selected').text());
                }

                // Address
                const addressParts = [
                    $('#street_address').val(),
                    $('#city').val(),
                    $('#state').val(),
                    $('#postal_code').val(),
                    $('#country').val()
                ];
                $('#review-address').text(addressParts.filter(Boolean).join(', '));
            }

            // Next button click handler
            $('#next-btn').on('click', function () {
                console.log("click")
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                        if (currentStep === totalSteps) {
                            updateReviewSection();
                        }
                    }
                }
            });

            // Previous button click handler
            $('#prev-btn').on('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Form submission handler
            $('form').on('submit', function (e) {
                e.preventDefault();
                if (validateCurrentStep()) {
                    const submitBtn = $('#submit-btn');
                    const spinner = submitBtn.find('svg');
                    const text = submitBtn.find('span:first');

                    submitBtn.prop('disabled', true);
                    spinner.removeClass('hidden');
                    text.text('Submitting...');

                    // Submit the form
                    this.submit();
                }
            });
        });
    </script>
@endsection
