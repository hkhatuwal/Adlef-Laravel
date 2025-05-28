@extends('_partials.app',['title' => config('app.name').' | Help Request #'.$helpRequest->id,'description' => 'View details of your help request','image' => asset('assets/images/savings.svg'),'isDark' => true])

@section('content')
    <div class="container mx-auto p-6 py-24">
        <!-- Heading Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-bold">Help Request #{{ $helpRequest->id }}</h1>
                <a href="{{ route('frontend.help-center.my-requests') }}" class="text-blue-400 hover:text-blue-300 font-medium">
                    ← Back to My Requests
                </a>
            </div>
            <div class="flex flex-wrap gap-4 text-sm">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($helpRequest->status == 'open') bg-blue-100 text-blue-800
                    @elseif($helpRequest->status == 'in_progress') bg-yellow-100 text-yellow-800
                    @elseif($helpRequest->status == 'resolved') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $helpRequest->status)) }}
                </span>
                <span class="px-2 py-1 rounded text-xs font-medium
                    @if($helpRequest->priority == 'low') bg-green-100 text-green-800
                    @elseif($helpRequest->priority == 'medium') bg-yellow-100 text-yellow-800
                    @elseif($helpRequest->priority == 'high') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($helpRequest->priority) }} Priority
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">{{ $helpRequest->subject }}</h2>
                    
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-300 mb-2">Your Message:</h3>
                        <div class="bg-gray-700 p-4 rounded-md">
                            <p class="text-gray-200 whitespace-pre-wrap">{{ $helpRequest->message }}</p>
                        </div>
                    </div>

                    @if($helpRequest->attachments && count($helpRequest->attachments) > 0)
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-300 mb-2">Attachments:</h3>
                            <div class="space-y-2">
                                @foreach($helpRequest->attachments as $attachment)
                                    <div class="flex items-center justify-between bg-gray-700 p-3 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <span class="text-gray-200">{{ $attachment['original_name'] }}</span>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ number_format($attachment['size'] / 1024, 1) }} KB
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Admin Response -->
                @if($helpRequest->admin_response)
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg border-l-4 border-green-500">
                        <h3 class="text-xl font-semibold text-green-400 mb-4">Admin Response</h3>
                        <div class="bg-gray-700 p-4 rounded-md mb-4">
                            <p class="text-gray-200 whitespace-pre-wrap">{{ $helpRequest->admin_response }}</p>
                        </div>
                        @if($helpRequest->responded_at)
                            <div class="text-sm text-gray-400">
                                Responded on {{ $helpRequest->responded_at->format('F j, Y \a\t g:i A') }}
                                @if($helpRequest->respondedBy)
                                    by {{ $helpRequest->respondedBy->name }}
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
                        <h3 class="text-xl font-semibold text-yellow-400 mb-2">Waiting for Response</h3>
                        <p class="text-gray-300">Our support team will review your request and respond as soon as possible.</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Request Information -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Request Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-400">Category:</span>
                            <span class="ml-2 text-gray-200">{{ $helpRequest->category->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Priority:</span>
                            <span class="ml-2 px-2 py-1 rounded text-xs font-medium
                                @if($helpRequest->priority == 'low') bg-green-100 text-green-800
                                @elseif($helpRequest->priority == 'medium') bg-yellow-100 text-yellow-800
                                @elseif($helpRequest->priority == 'high') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($helpRequest->priority) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400">Status:</span>
                            <span class="ml-2 px-2 py-1 rounded text-xs font-medium
                                @if($helpRequest->status == 'open') bg-blue-100 text-blue-800
                                @elseif($helpRequest->status == 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($helpRequest->status == 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $helpRequest->status)) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400">Submitted:</span>
                            <span class="ml-2 text-gray-200">{{ $helpRequest->created_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                        @if($helpRequest->updated_at != $helpRequest->created_at)
                            <div>
                                <span class="text-gray-400">Last Updated:</span>
                                <span class="ml-2 text-gray-200">{{ $helpRequest->updated_at->format('F j, Y \a\t g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('frontend.help-center') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded-md transition duration-300">
                            Submit New Request
                        </a>
                        <a href="{{ route('frontend.help-center.my-requests') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold py-2 px-4 rounded-md transition duration-300">
                            View All Requests
                        </a>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Need Immediate Help?</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-400">Email:</span>
                            <span class="ml-2 text-blue-400">{{ config('app.email', 'support@adlef.com') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Phone:</span>
                            <span class="ml-2 text-gray-200">+1 209 890 0004</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Hours:</span>
                            <span class="ml-2 text-gray-200">Mon-Fri 9AM-6PM EST</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 