@extends('_partials.app',['title' => config('app.name').' | Help Center','description' => 'Get help and support for your account and services','image' => asset('assets/images/savings.svg'),'isDark' => true])

@section('content')
    <div class="container mx-auto p-6 py-24">
        <!-- Heading Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Help Center</h1>
            <p class="text-lg text-gray-600">Need assistance? Submit a help request and our support team will get back to you.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Help Request Form -->
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Submit a Help Request</h2>

                @auth
                    <form action="{{ route('frontend.help-center.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Category Selection -->
                        <div class="mb-6">
                            <label for="help_center_category_id" class="block text-sm font-medium text-gray-300 mb-2">Category *</label>
                            <select name="help_center_category_id" id="help_center_category_id" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('help_center_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-medium text-gray-300 mb-2 bg-transparent">Subject *</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full px-3 py-2 !bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of your issue">
                        </div>

                        <!-- Priority -->
                        <div class="mb-6">
                            <label for="priority" class="block text-sm font-medium text-gray-300 mb-2">Priority *</label>
                            <select name="priority" id="priority" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message *</label>
                            <textarea name="message" id="message" rows="6" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Please describe your issue in detail">{{ old('message') }}</textarea>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-6">
                            <label for="attachments" class="block text-sm font-medium text-gray-300 mb-2">Attachments (Optional)</label>
                            <input type="file" name="attachments[]" id="attachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-400 mt-1">Max 10MB per file. Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-300">
                            Submit Help Request
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400 mb-4">Please sign in to submit a help request.</p>
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                            Sign In
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Quick Links & Information -->
            <div class="space-y-6">
                <!-- My Requests -->
                @auth
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">My Help Requests</h3>
                        <p class="text-gray-400 mb-4">View and track your submitted help requests.</p>
                        <a href="{{ route('frontend.help-center.my-requests') }}" class="text-blue-500 font-semibold hover:underline">
                            View My Requests →
                        </a>
                    </div>
                @endauth

                <!-- Contact Information -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Other Ways to Contact Us</h3>
                    <div class="space-y-3">
                        <div>
                            <h4 class="font-medium">Email Support</h4>
                            <p class="text-gray-400">{{ config('app.email', 'support@adlef.com') }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium">Phone Support</h4>
                            <p class="text-gray-400">+1 209 890 0004</p>
                        </div>
                        <div>
                            <h4 class="font-medium">Business Hours</h4>
                            <p class="text-gray-400">Monday - Friday: 9:00 AM - 6:00 PM EST</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Frequently Asked Questions</h3>
                    <p class="text-gray-400 mb-4">Find quick answers to common questions.</p>
                    <a href="#" class="text-blue-500 font-semibold hover:underline">
                        Browse FAQ →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
