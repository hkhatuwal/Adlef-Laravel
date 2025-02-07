<div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Add Crypto Wallet</h1>
        <a href="#" class="text-gray-500 hover:text-gray-700">
            <span class="text-xl">&times;</span>
        </a>
    </div>

    <form action="#" method="POST">
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
                <select name="network" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Select Network</option>
                    <option value="ethereum">Ethereum (ETH)</option>
                    <option value="bitcoin">Bitcoin (BTC)</option>
                    <option value="binance">Binance Smart Chain (BSC)</option>
                    <option value="polygon">Polygon (MATIC)</option>
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
