@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Help Request #{{ $helpRequest->id }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $helpRequest->subject }}</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Status and Priority Badges -->
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                @if($helpRequest->priority == 'low') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400
                @elseif($helpRequest->priority == 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400
                @elseif($helpRequest->priority == 'high') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400
                @else bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-400
                @endif">
                {{ ucfirst($helpRequest->priority) }} Priority
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                @if($helpRequest->status == 'open') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400
                @elseif($helpRequest->status == 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400
                @elseif($helpRequest->status == 'resolved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400
                @else bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-400
                @endif">
                {{ ucfirst(str_replace('_', ' ', $helpRequest->status)) }}
            </span>
            <a href="{{ route('admin.help-center.index') }}" 
               class="inline-flex items-center bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                <i class="material-symbols-outlined mr-2 text-lg">arrow_back</i>
                Back
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2 text-sm">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-start">
                <i class="material-symbols-outlined text-red-500 mr-2 text-sm mt-0.5">error</i>
                <div>
                    <p class="text-red-600 dark:text-red-400 font-medium text-sm mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-600 dark:text-red-400 text-sm space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Grid - Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Request Details & Message -->
        <div class="lg:col-span-2">
            <!-- Request Info & User Info Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Request Information -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                        <i class="material-symbols-outlined mr-2 text-base">info</i>
                        Request Details
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Category</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $helpRequest->category->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Submitted</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $helpRequest->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($helpRequest->updated_at != $helpRequest->created_at)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Updated</span>
                                <span class="font-medium text-slate-900 dark:text-white">{{ $helpRequest->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                        <i class="material-symbols-outlined mr-2 text-base">person</i>
                        User Information
                    </h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                    {{ strtoupper(substr($helpRequest->user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="font-medium text-slate-900 dark:text-white text-sm truncate">{{ $helpRequest->user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $helpRequest->user->email }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Member since {{ $helpRequest->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Message -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4 mb-6">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center">
                    <i class="material-symbols-outlined mr-2 text-base">message</i>
                    User Message
                </h3>
                <div class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded border border-slate-200 dark:border-slate-600">
                    <p class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap text-sm leading-relaxed">{{ $helpRequest->message }}</p>
                </div>
                
                <!-- Attachments (if any) -->
                @if($helpRequest->attachments && count($helpRequest->attachments) > 0)
                    <div class="mt-4">
                        <h4 class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-2">Attachments ({{ count($helpRequest->attachments) }})</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($helpRequest->attachments as $attachment)
                                <div class="flex items-center bg-slate-50 dark:bg-slate-700/50 p-2 rounded border border-slate-200 dark:border-slate-600">
                                    <i class="material-symbols-outlined text-slate-400 mr-2 text-sm flex-shrink-0">description</i>
                                    <span class="text-slate-900 dark:text-slate-100 truncate text-xs">{{ $attachment['original_name'] }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-2 flex-shrink-0">{{ number_format($attachment['size'] / 1024, 1) }}KB</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Admin Response Section -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                    <i class="material-symbols-outlined mr-2 text-base">support_agent</i>
                    Admin Response
                </h3>
                
                <!-- Current Response Display -->
                @if($helpRequest->admin_response)
                    <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-emerald-800 dark:text-emerald-400 text-xs flex items-center">
                                <i class="material-symbols-outlined mr-1 text-sm">check_circle</i>
                                Current Response
                            </h4>
                            @if($helpRequest->responded_at)
                                <span class="text-xs text-emerald-600 dark:text-emerald-400">
                                    {{ $helpRequest->responded_at->format('M j, g:i A') }}
                                    @if($helpRequest->respondedBy)
                                        by {{ $helpRequest->respondedBy->name }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <p class="text-emerald-700 dark:text-emerald-300 whitespace-pre-wrap text-sm leading-relaxed">{{ $helpRequest->admin_response }}</p>
                    </div>
                @endif

                <!-- Response Form -->
                <form action="{{ route('admin.help-center.update', $helpRequest) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="status" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Update Status</label>
                            <select name="status" id="status" required 
                                    class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                                <option value="open" {{ $helpRequest->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $helpRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $helpRequest->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $helpRequest->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-3 rounded transition-colors text-sm flex items-center justify-center">
                                <i class="material-symbols-outlined mr-1 text-sm">send</i>
                                Update
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="admin_response" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            {{ $helpRequest->admin_response ? 'Update Response' : 'Add Response' }}
                        </label>
                        <textarea name="admin_response" id="admin_response" rows="4" 
                                  class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white resize-vertical"
                                  placeholder="Enter your response to the user...">{{ old('admin_response', $helpRequest->admin_response) }}</textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Sidebar - Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4 sticky top-6">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                    <i class="material-symbols-outlined mr-2 text-base">bolt</i>
                    Quick Actions
                </h3>
                <div class="space-y-2">
                    @if($helpRequest->status !== 'in_progress')
                        <form action="{{ route('admin.help-center.update', $helpRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" 
                                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-3 rounded transition-colors text-sm flex items-center justify-center">
                                <i class="material-symbols-outlined mr-2 text-sm">pending</i>
                                Mark In Progress
                            </button>
                        </form>
                    @endif
                    @if($helpRequest->status !== 'resolved')
                        <form action="{{ route('admin.help-center.update', $helpRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded transition-colors text-sm flex items-center justify-center">
                                <i class="material-symbols-outlined mr-2 text-sm">check_circle</i>
                                Mark Resolved
                            </button>
                        </form>
                    @endif
                    @if($helpRequest->status !== 'closed')
                        <form action="{{ route('admin.help-center.update', $helpRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" 
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-3 rounded transition-colors text-sm flex items-center justify-center">
                                <i class="material-symbols-outlined mr-2 text-sm">close</i>
                                Close Request
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Request Statistics -->
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-3">Request Info</h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">ID</span>
                            <span class="font-medium text-slate-900 dark:text-white">#{{ $helpRequest->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Priority</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ ucfirst($helpRequest->priority) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $helpRequest->status)) }}</span>
                        </div>
                        @if($helpRequest->attachments && count($helpRequest->attachments) > 0)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Attachments</span>
                                <span class="font-medium text-slate-900 dark:text-white">{{ count($helpRequest->attachments) }} files</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 