@extends('admin._partials.admin_main')

@section('page-title', 'System Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf

            <!-- Transaction Costs Settings Group -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Transaction Costs</h2>
                
                <!-- OTC Transaction Cost Settings -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">OTC Transaction Cost</h3>
                    
                    <div class="space-y-4">
                        <!-- Cost Type Selection -->
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="settings[otc_cost_type]" value="percentage" 
                                    class="form-radio" 
                                    {{ ($settingsKeyValues['otc_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                <span class="ml-2">Percentage</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="settings[otc_cost_type]" value="fixed" 
                                    class="form-radio"
                                    {{ ($settingsKeyValues['otc_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                <span class="ml-2">Fixed Value</span>
                            </label>
                        </div>

                        <!-- Percentage Value -->
                        <div class="percentage-input {{ ($settingsKeyValues['otc_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                            <input type="number" name="settings[otc_cost_percentage]" 
                                value="{{ $settingsKeyValues['otc_cost_percentage'] ?? '0' }}"
                                step="0.01" min="0" max="100"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Fixed Value -->
                        <div class="fixed-input {{ ($settingsKeyValues['otc_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                            <input type="number" name="settings[otc_cost_fixed]" 
                                value="{{ $settingsKeyValues['otc_cost_fixed'] ?? '0' }}"
                                step="0.00000001" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Asset Transfer Cost Settings -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Asset Transfer Cost</h3>
                    
                    <div class="space-y-4">
                        <!-- Cost Type Selection -->
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="settings[transfer_cost_type]" value="percentage" 
                                    class="form-radio"
                                    {{ ($settingsKeyValues['transfer_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                <span class="ml-2">Percentage</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="settings[transfer_cost_type]" value="fixed" 
                                    class="form-radio"
                                    {{ ($settingsKeyValues['transfer_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                <span class="ml-2">Fixed Value</span>
                            </label>
                        </div>

                        <!-- Percentage Value -->
                        <div class="percentage-input {{ ($settingsKeyValues['transfer_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                            <input type="number" name="settings[transfer_cost_percentage]" 
                                value="{{ $settingsKeyValues['transfer_cost_percentage'] ?? '0' }}"
                                step="0.01" min="0" max="100"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Fixed Value -->
                        <div class="fixed-input {{ ($settingsKeyValues['transfer_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                            <input type="number" name="settings[transfer_cost_fixed]" 
                                value="{{ $settingsKeyValues['transfer_cost_fixed'] ?? '0' }}"
                                step="0.00000001" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Card Payment Providers Cost Settings -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Card Payment Providers Cost</h3>
                    
                    <div class="space-y-6">
                        <!-- NGenius Cost Settings -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="text-md font-medium mb-3">NGenius</h4>
                            <div class="space-y-4">
                                <!-- Cost Type Selection -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[ngenius_cost_type]" value="percentage" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['ngenius_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                        <span class="ml-2">Percentage</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[ngenius_cost_type]" value="fixed" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['ngenius_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                        <span class="ml-2">Fixed Value</span>
                                    </label>
                                </div>

                                <!-- Percentage Value -->
                                <div class="ngenius-percentage-input {{ ($settingsKeyValues['ngenius_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                                    <input type="number" name="settings[ngenius_cost_percentage]" 
                                        value="{{ $settingsKeyValues['ngenius_cost_percentage'] ?? '0' }}"
                                        step="0.01" min="0" max="100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Fixed Value -->
                                <div class="ngenius-fixed-input {{ ($settingsKeyValues['ngenius_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                                    <input type="number" name="settings[ngenius_cost_fixed]" 
                                        value="{{ $settingsKeyValues['ngenius_cost_fixed'] ?? '0' }}"
                                        step="0.00000001" min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- PayOp Cost Settings -->
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="text-md font-medium mb-3">PayOp</h4>
                            <div class="space-y-4">
                                <!-- Cost Type Selection -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[payop_cost_type]" value="percentage" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['payop_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                        <span class="ml-2">Percentage</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[payop_cost_type]" value="fixed" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['payop_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                        <span class="ml-2">Fixed Value</span>
                                    </label>
                                </div>

                                <!-- Percentage Value -->
                                <div class="payop-percentage-input {{ ($settingsKeyValues['payop_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                                    <input type="number" name="settings[payop_cost_percentage]" 
                                        value="{{ $settingsKeyValues['payop_cost_percentage'] ?? '0' }}"
                                        step="0.01" min="0" max="100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Fixed Value -->
                                <div class="payop-fixed-input {{ ($settingsKeyValues['payop_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                                    <input type="number" name="settings[payop_cost_fixed]" 
                                        value="{{ $settingsKeyValues['payop_cost_fixed'] ?? '0' }}"
                                        step="0.00000001" min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- PayDo Cost Settings -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="text-md font-medium mb-3">PayDo</h4>
                            <div class="space-y-4">
                                <!-- Cost Type Selection -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[paydo_cost_type]" value="percentage" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['paydo_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                        <span class="ml-2">Percentage</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[paydo_cost_type]" value="fixed" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['paydo_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                        <span class="ml-2">Fixed Value</span>
                                    </label>
                                </div>

                                <!-- Percentage Value -->
                                <div class="paydo-percentage-input {{ ($settingsKeyValues['paydo_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                                    <input type="number" name="settings[paydo_cost_percentage]" 
                                        value="{{ $settingsKeyValues['paydo_cost_percentage'] ?? '0' }}"
                                        step="0.01" min="0" max="100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Fixed Value -->
                                <div class="paydo-fixed-input {{ ($settingsKeyValues['paydo_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                                    <input type="number" name="settings[paydo_cost_fixed]" 
                                        value="{{ $settingsKeyValues['paydo_cost_fixed'] ?? '0' }}"
                                        step="0.00000001" min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crypto Payment Providers Cost Settings -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Crypto Payment Providers Cost</h3>
                    
                    <div class="space-y-6">
                        <!-- TronGrid Cost Settings -->
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="text-md font-medium mb-3">TronGrid</h4>
                            <div class="space-y-4">
                                <!-- Cost Type Selection -->
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[trongrid_cost_type]" value="percentage" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['trongrid_cost_type'] ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                        <span class="ml-2">Percentage</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="settings[trongrid_cost_type]" value="fixed" 
                                            class="form-radio"
                                            {{ ($settingsKeyValues['trongrid_cost_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                                        <span class="ml-2">Fixed Value</span>
                                    </label>
                                </div>

                                <!-- Percentage Value -->
                                <div class="trongrid-percentage-input {{ ($settingsKeyValues['trongrid_cost_type'] ?? 'percentage') == 'percentage' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Percentage Value (%)</label>
                                    <input type="number" name="settings[trongrid_cost_percentage]" 
                                        value="{{ $settingsKeyValues['trongrid_cost_percentage'] ?? '0' }}"
                                        step="0.01" min="0" max="100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Fixed Value -->
                                <div class="trongrid-fixed-input {{ ($settingsKeyValues['trongrid_cost_type'] ?? 'percentage') == 'fixed' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700">Fixed Value</label>
                                    <input type="number" name="settings[trongrid_cost_fixed]" 
                                        value="{{ $settingsKeyValues['trongrid_cost_fixed'] ?? '0' }}"
                                        step="0.00000001" min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Settings Group -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Notifications</h2>
                <div class="space-y-4">
                    @php
                        $globalNotification = $settingsKeyValues['global_notification'];
                        $globalNotificationText = $settingsKeyValues['global_notification_text'];
                    @endphp

                    <!-- Global Notification Toggle -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="font-semibold text-gray-700">Enable Global Notification</label>
                            <p class="text-sm text-gray-500">Show a notification banner to all users</p>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[global_notification]" class="sr-only peer"
                                    {{  $globalNotification == 'on' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                                    peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full
                                    peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px]
                                    after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Global Notification Text -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block font-semibold text-gray-700 mb-2">Notification Message</label>
                        <textarea name="settings[global_notification_text]" rows="3"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter the notification message here...">{{ $globalNotificationText ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // OTC Cost Type Toggle
        const otcCostTypeInputs = document.querySelectorAll('input[name="settings[otc_cost_type]"]');
        otcCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.mb-6');
                container.querySelector('.percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });

        // Transfer Cost Type Toggle
        const transferCostTypeInputs = document.querySelectorAll('input[name="settings[transfer_cost_type]"]');
        transferCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.mb-6');
                container.querySelector('.percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });

        // NGenius Cost Type Toggle
        const ngeniusCostTypeInputs = document.querySelectorAll('input[name="settings[ngenius_cost_type]"]');
        ngeniusCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.border-l-4');
                container.querySelector('.ngenius-percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.ngenius-fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });

        // PayOp Cost Type Toggle
        const payopCostTypeInputs = document.querySelectorAll('input[name="settings[payop_cost_type]"]');
        payopCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.border-l-4');
                container.querySelector('.payop-percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.payop-fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });

        // PayDo Cost Type Toggle
        const paydoCostTypeInputs = document.querySelectorAll('input[name="settings[paydo_cost_type]"]');
        paydoCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.border-l-4');
                container.querySelector('.paydo-percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.paydo-fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });

        // TronGrid Cost Type Toggle
        const trongridCostTypeInputs = document.querySelectorAll('input[name="settings[trongrid_cost_type]"]');
        trongridCostTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.border-l-4');
                container.querySelector('.trongrid-percentage-input').classList.toggle('hidden', this.value !== 'percentage');
                container.querySelector('.trongrid-fixed-input').classList.toggle('hidden', this.value !== 'fixed');
            });
        });
    });
</script>
@endpush
@endsection
