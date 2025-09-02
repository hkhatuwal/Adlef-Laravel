<div id="createApiKeyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-4 border w-full max-w-xl shadow-lg rounded-lg bg-white">
        <div class="mt-2">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create API Key</h3>
                <button onclick="closeCreateApiKeyModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fa-solid fa-times text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="createApiKeyForm" class="mt-4 space-y-4">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Environment</label>
                            <select name="is_sandbox"
                                    class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black">
{{--                                <option value="1">Sandbox</option>--}}
                                <option value="0">Production</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email"
                                   class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                            <input type="text" name="company_name"
                                   class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black">
                        </div>
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allowed IP Addresses</label>
                        <textarea name="allowed_ips" rows="2"
                                  class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black resize-none"
                                  placeholder="192.168.1.1 (one per line, leave empty for all)"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Webhook URLs</label>
                        <textarea name="webhook_urls" rows="2"
                                  class="w-full px-3 py-2 text-sm border-gray-300 rounded-md focus:ring-black focus:border-black resize-none"
                                  placeholder="https://yoursite.com/webhook (one per line)"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Currencies</label>
                        <div class="grid grid-cols-4 gap-2">
                            @php
                                $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'CNY', 'INR'];
                            @endphp
                            @foreach($currencies as $currency)
                                <label class="flex items-center space-x-1">
                                    <input type="checkbox" name="allowed_currencies[]" value="{{ $currency }}"
                                           class="rounded border-gray-300 text-black focus:ring-black text-sm">
                                    <span class="text-sm">{{ $currency }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave unchecked to allow all currencies</p>
                    </div>
                </div>
            </form>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-4">
                <button onclick="closeCreateApiKeyModal()"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="submitCreateApiKey()"
                        class="px-4 py-2 text-sm bg-black text-white rounded-md hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i>
                    Create API Key
                </button>
            </div>
        </div>
    </div>
</div>
