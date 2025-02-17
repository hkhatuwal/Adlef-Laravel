@extends('client._layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Currency Exchange
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Exchange your digital assets instantly
            </p>
        </div>

        <form action="{{ route('client.otc.confirm') }}" method="POST"
              class="bg-white rounded-lg shadow-sm border border-gray-100 " id="exchange-form">
            @csrf
            <div class="p-4">
                <!-- You Pay Section -->
                <div class="mb-4 car">
                    <div class="flex bg-white h-28 items-start rounded-md flex-col justify-center card shadow-lg p-2">
                        <label class="text-xs w-full h-10 flex items-center font-semibold text-gray-500   p-2">YOU
                            PAY</label>
                        <div class="flex flex-row w-full bg-white justify-between items-center px-3 py-2 relative">
                            <input id="fromAmount" name="from_amount"
                                   class="amount-input font-bold text-2xl focus-visible:border-none focus:outline-none w-2/3"
                                   placeholder="0.00" min="0" step="any" value="{{ old('from_amount') }}" required>
                            <span class="currency-selector pay-currency-btn">
                                <input type="hidden" name="from_currency" id="from-currency" class="currency"
                                       value="{{ old('from_currency') }}">
                                <span class="mr-2"><img src="{{asset('storage/logo/bitcoin.svg')}}"
                                                        class="currency-icon w-5 h-5" alt="image"/> </span>
                                <span class="currency-code">BTC</span> <i class="fa-solid fa-angle-down ml-1"></i>
                            </span>
                            <div class="currency-dialog pay-currency-dialog absolute bg-white shadow-lg rounded-lg p-3 w-64 z-10 top-12 right-0"
                                 style="display: none;">
                                <input type="text" class="currency-search pay-currency-search "
                                       placeholder="Search currency...">
                                <ul class="currency-list pay-currency-list max-h-48 overflow-y-auto">
                                    @foreach($currencies as $currency)
                                        <li class="currency-item flex items-center p-1.5 hover:bg-gray-100 cursor-pointer text-sm"
                                            data-balance="{{$currency->getMyAssetAccount()->balance}}"
                                            data-type="pay-currency"
                                            data-currency_type="{{$currency->type}}"
                                            data-value="{{$currency->id}}">
                                            <img src="{{ asset('storage/'.$currency->icon) }}"
                                                 class="currency-icon w-5 h-5 mr-2">
                                            <span class="currency-name">{{$currency->symbol}}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="balance-info flex items-center justify-between px-3 py-1  text-xs w-full ">
                            <span class="text-gray-500">Available Balance</span>
                            <span class="available-balance font-medium text-gray-900"
                                  id="availableBalance">$ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Exchange Icon -->
                <div class="exchange-icon-wrapper relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <button type="button"
                                class="switch-currencies-btn inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- You Receive Section -->
                <div class="mb-4">
                    <div class="flex bg-white h-28 items-start rounded-md flex-col justify-center card p-2">
                        <label class="text-xs w-full h-6 flex items-center font-semibold text-gray-500 bg-white p-2">YOU
                            RECEIVE</label>
                        <div class="flex flex-row w-full bg-white justify-between items-center px-3 py-2 relative">
                            <input id="toAmount" name="to_amount"
                                   class="amount-input receive-amount font-bold text-2xl focus-visible:border-none focus:outline-none w-2/3 bg-white cursor-not-allowed opacity-70"
                                   placeholder="0.00" readonly>
                            <span class="currency-selector receive-currency-btn">
                                <input type="hidden" name="to_currency" id="to-currency" class="currency"
                                       value="{{ old('to_currency') }}">
                                <span class="mr-2"><img src="{{asset('storage/logo/bitcoin.svg')}}"
                                                        class="currency-icon w-5 h-5" alt="image"/> </span>
                                <span class="currency-code">BTC</span> <i class="fa-solid fa-angle-down ml-1"></i>
                            </span>
                            <div class="currency-dialog receive-currency-dialog absolute bg-white shadow-lg rounded-lg p-3 w-64 z-10 top-12 right-0"
                                 style="display: none;">
                                <input type="text" class="currency-search receive-currency-search "
                                       placeholder="Search currency...">
                                <ul class="currency-list receive-currency-list max-h-48 overflow-y-auto">
                                    @foreach($currencies as $currency)
                                        <li class="currency-item flex items-center p-1.5 hover:bg-gray-100 cursor-pointer text-sm"
                                            data-value="{{$currency->id}}"  data-type="receive">
                                            <img src="{{ asset('storage/'.$currency->icon) }}"
                                                 class="currency-icon w-5 h-5 mr-2">
                                            <span class="currency-name">{{$currency->symbol}}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="px-3 py-1 text-xs bg-gray-50 text-gray-500">
                            Estimated amount
                        </div>
                    </div>
                </div>

                <!-- Exchange Info -->
                <div class="bg-gray-50 rounded-lg p-3 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Exchange Rate</span>
                        <span class="exchange-rate font-medium text-gray-900" id="exchangeRate">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Network Fee</span>
                        <span class="network-fee font-medium text-gray-900" id="networkFee">N/A</span>
                    </div>
                </div>

                <!-- Terms and Button -->
                <div class="mt-4 space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="terms" name="terms"
                               class="terms-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               required>
                        <label for="terms" class="ml-2 text-xs text-gray-500">
                            I agree to the <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Terms and
                                Conditions</a>
                        </label>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 p-3 rounded-lg text-xs">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 text-green-500 p-3 rounded-lg text-xs">
                            {{ session('success') }}
                        </div>
                    @endif

                    <button type="submit"
                            class="confirm-exchange-btn w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                            disabled>
                        Confirm Exchange
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
