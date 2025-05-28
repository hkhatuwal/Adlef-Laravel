@extends('admin._partials.admin_main')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Help Center</h1>
                <p class="mt-2 text-base text-slate-600 dark:text-slate-400">Manage help requests and support tickets</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.help-center.categories') }}" 
                   class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="material-symbols-outlined mr-2 text-lg">category</i>
                    Manage Categories
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-400 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <i class="material-symbols-outlined text-emerald-500 mr-3 text-xl">check_circle</i>
                    <p class="text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <i class="material-symbols-outlined text-red-500 mr-3 text-xl">error</i>
                    <p class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Total Requests</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <i class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">help</i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Open</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['open'] }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl">
                            <i class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">schedule</i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">In Progress</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['in_progress'] }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                            <i class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-2xl">pending</i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Resolved</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['resolved'] }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <i class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Requests Table -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Recent Help Requests</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">View and manage all support tickets</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Request</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Priority</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($helpRequests as $request)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors duration-150">
                                <td class="px-6 py-5">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white leading-5">
                                            {{ Str::limit($request->subject, 50) }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                                            #{{ $request->id }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                                                <span class="text-white text-sm font-semibold">
                                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                                {{ $request->user->name }}
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400 truncate">
                                                {{ $request->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200">
                                        {{ $request->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($request->priority == 'low') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($request->priority == 'high') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                        @endif">
                                        <span class="w-2 h-2 rounded-full mr-2
                                            @if($request->priority == 'low') bg-green-500
                                            @elseif($request->priority == 'medium') bg-yellow-500
                                            @elseif($request->priority == 'high') bg-red-500
                                            @else bg-gray-500
                                            @endif"></span>
                                        {{ ucfirst($request->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($request->status == 'open') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($request->status == 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($request->status == 'resolved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                        @endif">
                                        <span class="w-2 h-2 rounded-full mr-2
                                            @if($request->status == 'open') bg-blue-500
                                            @elseif($request->status == 'in_progress') bg-yellow-500
                                            @elseif($request->status == 'resolved') bg-green-500
                                            @else bg-gray-500
                                            @endif"></span>
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm text-slate-900 dark:text-white font-medium">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $request->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('admin.help-center.show', $request) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 transition-colors duration-150">
                                        <i class="material-symbols-outlined text-lg">visibility</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-2xl">help</i>
                                        </div>
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <p class="text-lg font-medium">No help requests found</p>
                                            <p class="text-sm">Help requests will appear here when users submit them.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($helpRequests->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    {{ $helpRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 