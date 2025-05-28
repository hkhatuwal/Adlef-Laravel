@extends('_partials.app',['title' => config('app.name').' | My Help Requests','description' => 'View and track your submitted help requests','image' => asset('assets/images/savings.svg'),'isDark' => true])

@section('content')
    <div class="container mx-auto p-6 py-24">
        <!-- Heading Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">My Help Requests</h1>
            <p class="text-lg text-gray-600">Track the status of your submitted help requests.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Your Requests</h2>
            <a href="{{ route('frontend.help-center') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                Submit New Request
            </a>
        </div>

        @if($helpRequests->count() > 0)
            <div class="space-y-4">
                @foreach($helpRequests as $request)
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold mb-2">
                                    <a href="{{ route('frontend.help-center.show', $request) }}" class="text-blue-400 hover:text-blue-300">
                                        {{ $request->subject }}
                                    </a>
                                </h3>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                                    <span>Category: {{ $request->category->name }}</span>
                                    <span>Priority: 
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($request->priority == 'low') bg-green-100 text-green-800
                                            @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                                            @elseif($request->priority == 'high') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </span>
                                    <span>Submitted: {{ $request->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($request->status == 'open') bg-blue-100 text-blue-800
                                    @elseif($request->status == 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-gray-300 mb-4 line-clamp-2">{{ Str::limit($request->message, 150) }}</p>
                        
                        @if($request->admin_response)
                            <div class="bg-gray-700 p-4 rounded-md mb-4">
                                <h4 class="font-medium text-green-400 mb-2">Admin Response:</h4>
                                <p class="text-gray-300">{{ Str::limit($request->admin_response, 100) }}</p>
                                @if($request->responded_at)
                                    <p class="text-xs text-gray-400 mt-2">Responded on {{ $request->responded_at->format('M d, Y H:i') }}</p>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-400">
                                Request #{{ $request->id }}
                            </div>
                            <a href="{{ route('frontend.help-center.show', $request) }}" class="text-blue-400 hover:text-blue-300 font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $helpRequests->links() }}
            </div>
        @else
            <div class="bg-gray-800 p-12 rounded-lg shadow-lg text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">No Help Requests Yet</h3>
                <p class="text-gray-400 mb-6">You haven't submitted any help requests yet.</p>
                <a href="{{ route('frontend.help-center') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                    Submit Your First Request
                </a>
            </div>
        @endif
    </div>
@endsection 