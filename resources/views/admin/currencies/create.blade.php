@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Add New Currency</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Create a new currency in the system</p>
            </div>
            <a href="{{ route('admin.currencies.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                <i class="material-symbols-outlined text-[20px] mr-2">arrow_back</i>
                Back to Currencies
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="material-symbols-outlined text-red-500 mr-2">error</i>
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Please fix the following errors:</h3>
                </div>
                <ul class="ml-6 list-disc text-sm text-red-600 dark:text-red-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.currencies.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white dark:bg-slate-800 rounded-xl shadow-sm divide-y divide-slate-200 dark:divide-slate-700">
            @csrf

            <!-- Basic Information -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Basic Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Currency Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g., US Dollar"
                            required>
                    </div>

                    <div>
                        <label for="symbol" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Symbol</label>
                        <input type="text" name="symbol" id="symbol" value="{{ old('symbol') }}"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g., USD"
                            required>
                    </div>
                </div>
            </div>

            <!-- Currency Details -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Currency Details</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="">Select Type</option>
                            <option value="fiat" {{ old('type') === 'fiat' ? 'selected' : '' }}>Fiat Currency</option>
                            <option value="crypto" {{ old('type') === 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                        </select>
                    </div>

                    <div>
                        <label for="conversion_rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Conversion Rate</label>
                        <input type="number" step="0.000001" name="conversion_rate" id="conversion_rate" value="{{ old('conversion_rate') }}"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g., 1.000000"
                            required>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="active" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Enable or disable this currency</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', '1') ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Icon Upload -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Currency Icon</h2>
                <div class="mt-1 flex items-center">
                    <span class="inline-block h-16 w-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                        <img id="icon-preview" src="{{ asset('assets/images/image-placehoder.png') }}" alt="Icon preview"
                             class="h-16 w-16 object-contain">
                    </span>
                    <label class="ml-5 relative cursor-pointer">
                        <span class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="material-symbols-outlined text-[20px] mr-2">upload</i>
                            Upload Icon
                        </span>
                        <input type="file" name="icon" id="icon" accept="image/*" class="sr-only" required
                               onchange="document.getElementById('icon-preview').src = window.URL.createObjectURL(this.files[0])">
                    </label>
                    <p class="ml-4 text-xs text-slate-500 dark:text-slate-400">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="window.location.href='{{ route('admin.currencies.index') }}'"
                            class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="material-symbols-outlined text-[20px] mr-2">save</i>
                        Create Currency
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
