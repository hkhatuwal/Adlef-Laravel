@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Create Deposit Account</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.deposit-accounts.store') }}" method="POST" id="depositAccountForm">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Account Name</label>
                            <input type="text"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_type" class="block text-sm font-medium text-gray-700">Account Type</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('account_type') border-red-500 @enderror"
                                    id="account_type"
                                    name="account_type"
                                    required>
                                <option value="">Select Account Type</option>
                                <option value="Bank" {{ old('account_type') == 'Bank' ? 'selected' : '' }}>Bank Account</option>
                                <option value="CryptoWallet" {{ old('account_type') == 'CryptoWallet' ? 'selected' : '' }}>Crypto Wallet</option>
                            </select>
                            @error('account_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-medium text-gray-800">Custom Fields</h4>
                            </div>
                            <div class="p-6">
                                <div id="customFields" class="space-y-4"></div>
                                <button type="button"
                                        class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        onclick="addField()">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Field
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-6">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create Deposit Account
                            </button>
                            <a href="{{ route('admin.deposit-accounts.index') }}"
                               class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script>
    let fieldCounter = 0;

    function addField() {
        const fieldHtml = `
            <div class="field-row bg-gray-50 rounded-lg p-6 relative" id="field-${fieldCounter}">
                <button type="button"
                        class="absolute right-4 top-4 text-gray-400 hover:text-gray-600"
                        onclick="removeField(${fieldCounter})">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Field Name</label>
                        <input type="text"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               name="fields[${fieldCounter}][field_name]"
                               required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Field Value</label>
                        <input type="text"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               name="fields[${fieldCounter}][field_value]"
                               required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Field Type</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                name="fields[${fieldCounter}][field_type]"
                                required>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="email">Email</option>
                            <option value="tel">Telephone</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Display Order</label>
                        <input type="number"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               name="fields[${fieldCounter}][display_order]"
                               value="${fieldCounter}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">&nbsp;</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       id="required-${fieldCounter}"
                                       name="fields[${fieldCounter}][is_required]"
                                       value="1">
                                <span class="ml-2 text-sm text-gray-700">Required</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('customFields').insertAdjacentHTML('beforeend', fieldHtml);
        fieldCounter++;
    }

    function removeField(index) {
        document.getElementById(`field-${index}`).remove();
    }

    // Add at least one field by default
    document.addEventListener('DOMContentLoaded', function() {
        addField();
    });
</script>
@endsection
