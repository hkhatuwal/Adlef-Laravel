@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-3">I want to transfer...</h3>
                <p class="text-gray-500 text-lg mb-8">Select your currency and transfer option to proceed</p>
                <!-- Currency Selector -->
                <div class="max-w-md mx-auto">
                    <div class="relative">
                        <select id="currency_select" name="currency_id" class="select2">

                            @foreach(\App\Models\Currency::all() as $currency)
                                <option value="{{ $currency->id }}" data-icon="{{ asset('storage/' . $currency->icon) }}">
                                    {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                        <div class="currency-icon absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6">
                            <!-- Icon will be inserted here via JavaScript -->
                        </div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Transfer In -->
                <div class="group">
                    <div class="transfer-option h-full bg-white border-2 border-gray-100 rounded-2xl p-8 text-center transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl hover:border-black cursor-pointer"
                         data-type="transfer-in" data-url="{{route('client.transfer.in')}}">
                        <div class="mb-6 relative">
                            <div class="w-16 h-16 mx-auto bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                                <img src="{{ asset('assets/images/transfer-in.svg') }}" alt="Transfer In" class="w-8 h-8">
                            </div>
                        </div>
                        <h6 class="text-xl font-semibold text-gray-900 mb-3">Transfer In</h6>
                        <p class="text-gray-500 leading-relaxed">Move assets into your FDT account</p>
                    </div>
                </div>

                <!-- Transfer Out -->
                <div class="group">
                    <div class="transfer-option h-full bg-white border-2 border-gray-100 rounded-2xl p-8 text-center transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl hover:border-black cursor-pointer"
                         data-type="transfer-out" data-url="{{route('client.transfer.out')}}">
                        <div class="mb-6 relative">
                            <div class="w-16 h-16 mx-auto bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-100 transition-colors duration-300">
                                <img src="{{ asset('assets/images/transfer-out.svg') }}" alt="Transfer Out" class="w-8 h-8">
                            </div>
                        </div>
                        <h6 class="text-xl font-semibold text-gray-900 mb-3">Transfer Out</h6>
                        <p class="text-gray-500 leading-relaxed">Transfer assets out to your account</p>
                    </div>
                </div>

                <!-- Transfer Out to Third Party -->
                <div class="group">
                    <div class="transfer-option h-full bg-white border-2 border-gray-100 rounded-2xl p-8 text-center transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl hover:border-black cursor-pointer"
                         data-type="third-party" data-url="{{route('client.account.add')}}">
                        <div class="mb-6 relative">
                            <div class="w-16 h-16 mx-auto bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-100 transition-colors duration-300">
                                <img src="{{ asset('assets/images/third-party.svg') }}" alt="Third Party Transfer" class="w-8 h-8">
                            </div>
                        </div>
                        <h6 class="text-xl font-semibold text-gray-900 mb-3">Transfer Out to Third Party</h6>
                        <p class="text-gray-500 leading-relaxed">Transfer assets to a third party account</p>
                    </div>
                </div>
            </div>

            <!-- Create Instruction Button -->
            <div class="text-center">
                <button type="button"
                        class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-white bg-black rounded-full hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                        id="createInstructionBtn" data-url="{{route('client.account.add')}}">
                    Create instruction
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
