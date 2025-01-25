@extends('_partials.app')
@section('content')

    <section class="container mx-auto px-4 md:p-10 mt-10 relative">
        <h3 class="text-6xl relative z-10">Individual <span class="font-bold">account <br> application</span></h3>
        <img src="{{asset('/assets/images/texcture1.avif')}}"
             class="absolute inset-0 w-full h-full object-cover z-0 opacity-20">
    </section>
    <div class="progress-bar bg-gray-200 h-5 w-full">
        <div class="progress bg-lime-300 w-1/4 h-full"></div>
    </div>
    <section class="container mx-auto px-4 md:p-10 mt-10">
        <div class="flex flex-row justify-start items-start gap-8">
            <div class="flex flex-col gap-2 sticky left-0 top-14 max-w-[18rem]">
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
                      action="{{route('frontend.client-registration.save')}}">
                    @csrf
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
                                <input type="text" name="first_name" id="first_name"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle
                                    name(s)</label>
                                <input type="text" name="middle_name" id="middle_name"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="last_name" class="block text-sm font-bold text-gray-700">Last / Family name
                                    *</label>
                                <input type="text" name="last_name" id="last_name"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="alias" class="block text-sm font-bold text-gray-700">Alias / Name in
                                    native characters</label>
                                <input type="text" name="alias" id="alias"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="date_of_birth" class="block text-sm font-bold text-gray-700">Date of birth
                                    *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="place_of_birth" class="block text-sm font-bold text-gray-700">Place of
                                    Birth</label>
                                <select name="place_of_birth" id="place_of_birth"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-sm shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm select2">
                                    <option>Hong Kong</option>
                                    <!-- Add other options -->
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="gender" class="block text-sm font-bold text-gray-700">Gender *</label>
                                <select name="gender" id="gender"
                                        class="w-full  sm:text-sm select2">
                                    <option>Select...</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital
                                    status</label>
                                <select name="marital_status" id="marital_status"
                                        class="w-full  select2">
                                    <option>Select...</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Divorced</option>
                                    <option>Widowed</option>
                                </select>
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
                                <div class="mt-4 grid grid-cols-3 gap-1">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="custody" name="purpose[]" type="checkbox" value="Custody"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="custody" class="font-medium text-gray-700">Custody</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="asset_servicing" name="purpose[]" type="checkbox"
                                                   value="AssetServicing"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="asset_servicing" class="font-medium text-gray-700">Asset
                                                Servicing</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="escrow" name="purpose[]" type="checkbox" value="Escrow"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="escrow" class="font-medium text-gray-700">Escrow</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="investments" name="purpose[]" type="checkbox" value="Investments"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="investments"
                                                   class="font-medium text-gray-700">Investments</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="treasury_services" name="purpose[]" type="checkbox"
                                                   value="TreasuryServices"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="treasury_services" class="font-medium text-gray-700">Treasury
                                                Services</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="other_purpose" name="purpose[]" type="checkbox" value="Other"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="other_purpose" class="font-medium text-gray-700">Other</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend class="text-base font-bold text-gray-900">What is the source of the funds for
                                    your future transactions with us?
                                </legend>
                                <div class="mt-4 grid grid-cols-3 gap-1">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="salary" name="source_funds[]" type="checkbox" value="Salary"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="salary" class="font-medium text-gray-700">Salary</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="inheritance" name="source_funds[]" type="checkbox"
                                                   value="Inheritance"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="inheritance"
                                                   class="font-medium text-gray-700">Inheritance</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="pension" name="source_funds[]" type="checkbox"
                                                   value="Pension/Savings/Fund/Employment"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="pension" class="font-medium text-gray-700">Pension/Savings/Fund/Employment</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="divorce_settlement" name="source_funds[]" type="checkbox"
                                                   value="DivorceSettlement"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="divorce_settlement" class="font-medium text-gray-700">Divorce
                                                Settlement</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="interest_income" name="source_funds[]" type="checkbox"
                                                   value="InterestIncome"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="interest_income" class="font-medium text-gray-700">Interest
                                                Income</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="sale_of_property" name="source_funds[]" type="checkbox"
                                                   value="SaleOfProperty"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="sale_of_property" class="font-medium text-gray-700">Sale Of
                                                Property</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="capital_gains" name="source_funds[]" type="checkbox"
                                                   value="CapitalGains"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="capital_gains" class="font-medium text-gray-700">Capital
                                                Gains/Dividends from Investment</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="gambling" name="source_funds[]" type="checkbox" value="Gambling"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="gambling" class="font-medium text-gray-700">Gambling</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="gift" name="source_funds[]" type="checkbox" value="Gift"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="gift" class="font-medium text-gray-700">Gift</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="other_source" name="source_funds[]" type="checkbox" value="Other"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="other_source" class="font-medium text-gray-700">Other</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mt-6">
                                <div class="flex items-center gap-2">
                                    <legend class="text-base font-bold text-gray-900">What is the source of your wealth?
                                        (Check all that apply)
                                    </legend>

                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-1">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_salary" name="wealth_source[]" type="checkbox" value="1"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_salary" class="font-medium text-gray-700">Salary</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_inheritance" name="wealth_source[]" type="checkbox"
                                                   value="2"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_inheritance" class="font-medium text-gray-700">Inheritance</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_divorce" name="wealth_source[]" type="checkbox" value="4"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_divorce" class="font-medium text-gray-700">Divorce
                                                Settlement</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_pension" name="wealth_source[]" type="checkbox" value="8"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_pension" class="font-medium text-gray-700">Pension/SavingsFromEmployment</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_property" name="wealth_source[]" type="checkbox"
                                                   value="16"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_property" class="font-medium text-gray-700">Sale Of
                                                Property</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_interest" name="wealth_source[]" type="checkbox"
                                                   value="32"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_interest" class="font-medium text-gray-700">Interest
                                                Income</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_capital" name="wealth_source[]" type="checkbox" value="64"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_capital" class="font-medium text-gray-700">Capital
                                                Gain/Dividends from investment</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_gambling" name="wealth_source[]" type="checkbox"
                                                   value="128"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_gambling"
                                                   class="font-medium text-gray-700">Gambling</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_gift" name="wealth_source[]" type="checkbox" value="256"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_gift" class="font-medium text-gray-700">Gift</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="wealth_other" name="wealth_source[]" type="checkbox"
                                                   value="other"
                                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="wealth_other" class="font-medium text-gray-700">Other</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div>
                                <label class="text-base font-bold text-gray-900">What is your current annual
                                    income?</label>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center">
                                        <input id="US250k" name="annual_income" type="radio" value="US250k"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="US250k" class="ml-3 block text-sm font-medium text-gray-700">
                                            Under US$250k
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="US250kToUS500k" name="annual_income" type="radio"
                                               value="US250kToUS500k"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="US250kToUS500k"
                                               class="ml-3 block text-sm font-medium text-gray-700">
                                            US$250k - US$500k
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="US500kToUS1mil" name="annual_income" type="radio"
                                               value="US500kToUS1mil"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="US500kToUS1mil"
                                               class="ml-3 block text-sm font-medium text-gray-700">
                                            US$500k - US$1mil
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="US1milToUS5mil" name="annual_income" type="radio"
                                               value="US1milToUS5mil"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="US1milToUS5mil"
                                               class="ml-3 block text-sm font-medium text-gray-700">
                                            US$1mil - US$5mil
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="OverUS5mil" name="annual_income" type="radio" value="OverUS5mil"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="OverUS5mil" class="ml-3 block text-sm font-medium text-gray-700">
                                            Over US$5mil
                                        </label>
                                    </div>
                                </div>
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
                                <select id="phone_country" name="phone_country"
                                        class="w-full select2">
                                    <option>+852 Hong Kong</option>
                                    <!-- Add other country codes -->
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone_number" class="block text-sm font-bold text-gray-700">Phone number
                                    *</label>
                                <input type="tel" name="phone_number" id="phone_number"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6">
                                <label for="street_address" class="block text-sm font-bold text-gray-700">Street Address
                                    *</label>
                                <input type="text" name="street_address" id="street_address"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6">
                                <label for="apartment" class="block text-sm font-bold text-gray-700">Apartment, suite,
                                    unit, building, floor, etc.</label>
                                <input type="text" name="apartment" id="apartment"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="city" class="block text-sm font-bold text-gray-700">City *</label>
                                <input type="text" name="city" id="city"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="state" class="block text-sm font-bold text-gray-700">State / Region
                                    *</label>
                                <input type="text" name="state" id="state"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
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

                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="flex-grow">
                                    <label class="text-base font-bold text-gray-900">Are you a tax resident of Hong
                                        Kong?</label>
                                </div>
                                <div class="ml-4">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                        <input type="checkbox" name="hk_tax_resident" id="hk_tax_resident"
                                               class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                        <label for="hk_tax_resident"
                                               class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="text-base font-medium text-gray-900">Tax Identification Number</label>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-center">
                                        <input id="tin_provided" name="tin_status" type="radio" value="provided"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="tin_provided" class="ml-3 block text-sm font-medium text-gray-700">
                                            Provided
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="tin_not_provided" name="tin_status" type="radio" value="not_provided"
                                               class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                        <label for="tin_not_provided"
                                               class="ml-3 block text-sm font-medium text-gray-700">
                                            Not Provided
                                        </label>
                                    </div>
                                </div>

                                <!-- TIN Input field - shown when "Provided" is selected -->
                                <div class="mt-4" id="tin_input_section" style="display: none;">
                                    <input type="text" name="tin_number" id="tin_number"
                                           class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Enter your TIN">
                                </div>

                                <!-- Reason for no TIN - shown when "Not Provided" is selected -->
                                <div class="mt-4 space-y-4" id="tin_reason_section" style="display: none;">
                                    <label class="text-base font-medium text-gray-900">Reason for no TIN</label>
                                    <div class="space-y-4">
                                        @foreach(config('constants.tin_reasons') as $key => $reason)
                                            <div class="flex items-center">
                                                <input id="{{$key}}" name="tin_reason" type="radio" value="{{$key}}"
                                                       class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                                <label for="{{$key}}"
                                                       class="ml-3 block text-sm font-medium text-gray-700">
                                                    {{$reason}}
                                                </label>
                                            </div>

                                        @endforeach

                                    </div>
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
                                <input type="email" name="email" id="email"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="password" class="block text-sm font-bold text-gray-700">Choose a password
                                    *</label>
                                <input type="password" name="password" id="password"
                                       class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm">
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
