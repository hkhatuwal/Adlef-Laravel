@extends('admin._partials.admin_main')

@section('page-title', 'System Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf

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
@endsection
