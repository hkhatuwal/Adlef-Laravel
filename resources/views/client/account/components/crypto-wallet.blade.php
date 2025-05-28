<div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Add Crypto Wallet</h1>
        <a href="#" class="text-gray-500 hover:text-gray-700">
            <span class="text-xl">&times;</span>
        </a>
    </div>

    <form action="{{ route('client.crypto-wallet.store') }}" method="POST" id="crypto-wallet-form">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet Name</label>
                <input type="text" name="wallet_name" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet Address</label>
                <input type="text" name="wallet_address" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Network</label>
                <select  name="network" class="w-full border rounded-lg px-3 py-2 select2" required>
                    <option value="">Select Network</option>
                    @foreach (\App\Models\Currency::where(['active'=>true,'type'=>\App\Models\Currency::TYPE_CRYPTO])->get() as $currency)
                        <option value="{{ $currency->id }}" data-icon="{{asset("storage/".$currency->icon)}}" class='currency-icon'>
                            {{ $currency->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full bg-black text-white py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors">
                Submit
            </button>
        </div>
    </form>
</div>
