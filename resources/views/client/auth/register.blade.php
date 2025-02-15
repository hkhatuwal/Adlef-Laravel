@extends('_partials.app')
@section('content')

    <section class="container mx-auto px-4 md:p-10 mt-10 relative">
        <h3 class="text-6xl relative z-10">Individual <span class="font-bold">account <br> application</span></h3>
        <img src="{{asset('/assets/images/texcture1.avif')}}"
             class="absolute inset-0 w-full h-full object-cover z-0 opacity-20">
    </section>
    <div class="progress-bar1 bg-gray-200 h-5 w-full">
        <div class="progress1 bg-lime-300 w-1/4 h-full"></div>
    </div>
    <section class="container mx-auto px-4 md:p-10 mt-10">
        <div class="flex  flex-col-reverse md:flex-row justify-start items-start gap-8">
            <div class="flex flex-col gap-2 sticky left-0 right-0 mx-auto md:mx-0 top-14 max-w-[18rem]">
                <div class="py-8 px-6 bg-black text-white ">
                    <ul class="flex flex-col gap-6">
                        <li class="flex gap-2 items-center text-primary "><i class="fa-regular fa-address-card"></i>Application
                            Details
                        </li>
                        <li class="flex gap-2 items-center "><i class="fa-solid fa-fingerprint text-white"></i>Verify
                            Information
                        </li>
                        <li class="flex gap-2 items-center "><i class="fa-regular fa-circle-check"></i>Confirmation</li>
                    </ul>
                </div>
                <div class="py-8 px-6 flex flex-col gap-4 items-center justify-center border ">
                    <div class="circle h-16 w-16 bg-primary  flex items-center justify-center rounded-full">
                        <i class="fal fa-shield-check text-2xl"></i>
                    </div>
                    <h2 class="font-bold text-xl">Secure Application</h2>
                    <p class="text-center">We use SSL encryption to ensure your personal information sent over is secure
                        and can't be intercepted by an attacker.</p>
                </div>
            </div>
            <div class="bg-white ">
                <form method="POST"
                      action="{{route('client-registration.save')}}">
                    @csrf
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Personal Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Personal Information</h3>
                            <p class="mt-1 text-sm text-gray-500">To begin with, we'll need to ask you for some personal
                                information. Nothing fancy, just a few things to fill out your profile and help us get
                                to know you better.</p>
                        </div>

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-2">
                                <label for="first_name" class="block text-sm font-bold text-gray-700">First name
                                    *</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('first_name') border-red-500 @enderror">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle
                                    name(s)</label>
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('middle_name') border-red-500 @enderror">
                                @error('middle_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="last_name" class="block text-sm font-bold text-gray-700">Last / Family name
                                    *</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('last_name') border-red-500 @enderror">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="alias" class="block text-sm font-bold text-gray-700">Alias / Name in
                                    native characters</label>
                                <input type="text" name="alias" id="alias" value="{{ old('alias') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('alias') border-red-500 @enderror">
                                @error('alias')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="date_of_birth" class="block text-sm font-bold text-gray-700">Date of birth
                                    *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('date_of_birth') border-red-500 @enderror">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="place_of_birth" class="block text-sm font-bold text-gray-700">Place of
                                    Birth</label>
                                <select name="place_of_birth" id="place_of_birth"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-sm shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm select2 @error('place_of_birth') border-red-500 @enderror">
                                    <option value="">Select...</option>
                                    <option value="Hong Kong" {{ old('place_of_birth') == 'Hong Kong' ? 'selected' : '' }}>Hong Kong</option>
                                </select>
                                @error('place_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="gender" class="block text-sm font-bold text-gray-700">Gender *</label>
                                <select name="gender" id="gender"
                                        class="w-full sm:text-sm select2 @error('gender') border-red-500 @enderror">
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital
                                    status</label>
                                <select name="marital_status" id="marital_status"
                                        class="w-full select2 @error('marital_status') border-red-500 @enderror">
                                    <option value="">Select...</option>
                                    <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('marital_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Economic Profile -->
                    <div class="pt-8 space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Economic Profile and Use of Services
                                and Account</h3>
                            <p class="mt-1 text-sm text-gray-500">Allow us to understand better on the economic side of
                                your profile and usage of the service.</p>
                        </div>

                        <div class="space-y-6">
                            <fieldset>
                                <legend class="text-base font-bold text-gray-900">Please indicate the purpose for
                                    opening an account/applying for a service? (Check all that apply)
                                </legend>
                                @error('purpose')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-4 grid grid-cols-3 gap-1">
                                    @foreach(config('constants.account_purposes') as $purpose)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="{{ $purpose }}" name="purpose[]" type="checkbox"
                                                       value="{{ $purpose }}"
                                                       @checked(is_array(old('purpose')) && in_array($purpose, old('purpose')))
                                                       class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded @error('purpose') border-red-500 @enderror">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="{{ $purpose }}" class="font-medium text-gray-700">{{ $purpose}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend class="text-base font-bold text-gray-900">What is the source of the funds for
                                    your future transactions with us?
                                </legend>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-1">
                                    @foreach(config('constants.source_funds') as $source)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="{{ Str::snake($source) }}" name="source_funds[]" type="checkbox" value="{{ $source }}"
                                                       @checked(is_array(old('source_funds')) && in_array($source, old('source_funds')))
                                                       class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="{{ Str::snake($source) }}" class="font-medium text-gray-700">{{ $source }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('source_funds')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </fieldset>

                            <fieldset class="mt-6">
                                <div class="flex items-center gap-2">
                                    <legend class="text-base font-bold text-gray-900">What is the source of your wealth?
                                        (Check all that apply)
                                    </legend>

                                </div>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-1">
                                    @foreach(config('constants.wealth_source') as  $source)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="wealth_{{ Str::snake($source) }}" name="wealth_source[]" type="checkbox" value="{{$source}}"
                                                       @checked(is_array(old('wealth_source')) && in_array($source, old('wealth_source')))
                                                       class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="wealth_{{ Str::snake($source) }}" class="font-medium text-gray-700">{{$source}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('wealth_source')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </fieldset>

                            <div>
                                <label class="text-base font-bold text-gray-900">What is your current annual
                                    income?</label>
                                <div class="mt-4 space-y-2">
                                    @foreach(['Under US$250k', 'US$250k - US$500k', 'US$500k - US$1mil', 'US$1mil - US$5mil', 'Over US$5mil'] as $income)
                                        <div class="flex items-center">
                                            <input id="{{ Str::snake($income) }}" name="annual_income" type="radio" value="{{ $income }}"
                                                   @checked(old('annual_income') == $income)
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                            <label for="{{ Str::snake($income) }}" class="ml-3 block text-sm font-medium text-gray-700">
                                                {{ $income }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('annual_income')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="pt-8 space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Contact Information</h3>
                            <p class="mt-1 text-sm text-gray-500">It's very important to keep your contact information
                                up-to-date at all times so that you receive important notifications about your
                                application and accounts.</p>
                        </div>

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone_country" class="block text-sm font-medium text-gray-700">Country
                                    code</label>
                                <select id="phone_country" name="phone_country" class="w-full select2">
                                    @foreach(config('constants.country_code_with_name') as $code=>$country)
                                        <option value="{{$code}}" @selected(old('phone_country') == $code)>{{"( ".$code." )    ".$country}}</option>
                                    @endforeach
                                </select>
                                @error('phone_country')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone_number" class="block text-sm font-bold text-gray-700">Phone number
                                    *</label>
                                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('phone_number') border-red-300 @enderror">
                                @error('phone_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="street_address" class="block text-sm font-bold text-gray-700">Street Address
                                    *</label>
                                <input type="text" name="street_address" id="street_address" value="{{ old('street_address') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('street_address') border-red-300 @enderror">
                                @error('street_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="apartment" class="block text-sm font-bold text-gray-700">Apartment, suite,
                                    unit, building, floor, etc.</label>
                                <input type="text" name="apartment" id="apartment" value="{{ old('apartment') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('apartment') border-red-300 @enderror">
                                @error('apartment')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="city" class="block text-sm font-bold text-gray-700">City *</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('city') border-red-300 @enderror">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="state" class="block text-sm font-bold text-gray-700">State / Region
                                    *</label>
                                <input type="text" name="state" id="state" value="{{ old('state') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('state') border-red-300 @enderror">
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tax Residency -->
                    <div class="pt-8 space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Tax Residency Declaration</h3>
                            <p class="mt-1 text-sm text-gray-500">Tax regulations requires us to establish your tax
                                residency status under CRS and FATCA. We may need to contact you for further information
                                based on your responses below.</p>
                        </div>

                        <div class="mt-6">
                            <label class="text-base font-medium text-gray-900">Tax Identification Number</label>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="tin_provided" name="tin_status" type="radio" value="provided"
                                           class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300"  @checked(old('tin_status')=='provided') >
                                    <label for="tin_provided" class="ml-3 block text-sm font-medium text-gray-700">
                                        Provided
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="tin_not_provided" name="tin_status" type="radio" value="not_provided"
                                           @checked(old('tin_status')=='not_provided')
                                           class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                    <label for="tin_not_provided" class="ml-3 block text-sm font-medium text-gray-700">
                                        Not Provided
                                    </label>
                                </div>
                            </div>

                            <!-- TIN Input field - shown when "Provided" is selected -->
                            <div class="mt-4" id="tin_input_section" style="display: {{old('tin_status')=='provided'?"block":'none'}};">
                                <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('tin_number') border-red-300 @enderror"
                                       placeholder="Enter your TIN">
                                @error('tin_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reason for no TIN - shown when "Not Provided" is selected -->
                            <div class="mt-4 space-y-4" id="tin_reason_section" style="display:  {{old('tin_status')=='not_provided'?"block":'none'}};">
                                <label class="text-base font-medium text-gray-900">Reason for no TIN</label>
                                <div class="space-y-4">
                                    @foreach(config('constants.tin_reasons') as $key=>$reason)
                                        <div class="flex items-center">
                                            <input id="{{$key}}" name="tin_reason" type="radio" value="{{$key}}" @checked(old('tin_reason')==$key)
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                            <label for="{{$key}}" class="ml-3 block text-sm font-medium text-gray-700">
                                                {{$reason}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Online ID -->
                    <div class="pt-8 space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Create an Online ID</h3>
                            <p class="mt-1 text-sm text-gray-500">We'll need to email you important information about
                                your application. You'll also need to choose a password in order to log in and complete
                                your application in any stage, and later for accessing our online services.</p>
                        </div>

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-bold text-gray-700">Your email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="password" class="block text-sm font-bold text-gray-700">Choose a password
                                    *</label>
                                <input type="password" name="password" id="password"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm @error('password') border-red-300 @enderror">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Certifications -->
                    <div class="pt-8 space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Certifications & Authorization</h3>
                            <p class="mt-1 text-sm text-gray-500">Before applying or utilizing products or services
                                electronically, you must read and indicate your acceptance of the terms outlined below.
                                If you do not consent, you will not be able to proceed with the online account opening
                                process.</p>
                        </div>

                        <div class="space-y-4">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox"
                                           class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-bold text-gray-700">I agree to the Client
                                        Authorization</label>
                                    <p class="text-gray-500">Please scroll all the way down before clicking the
                                        checkbox</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

@endsection
