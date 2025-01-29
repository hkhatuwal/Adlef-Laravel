<div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Add Third Party Bank Account</h1>
        <a href="#" class="text-gray-500 hover:text-gray-700">
            <span class="text-xl">&times;</span>
        </a>
    </div>

    <form action="#" method="POST">
        @csrf
        <input type="hidden" name="third_party_type" value="{{ request()->get('third_party_type') }}">

        @if(request()->get('third_party_type') === 'individual')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Individual Name</label>
                <input type="text" name="individual_name" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                <select name="relationship" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Select Relationship</option>
                    <option value="family">Family Member</option>
                    <option value="friend">Friend</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        @else
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <input type="text" name="company_name" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                <input type="text" name="registration_number" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>
        @endif

        <div class="space-y-4 mt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                <input type="text" name="bank_name" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                <input type="text" name="account_number" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                <input type="text" name="ifsc_code" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full bg-black text-white py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors">
                Submit
            </button>
        </div>
    </form>
</div>
