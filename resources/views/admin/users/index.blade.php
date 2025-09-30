@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Users</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage and monitor your platform's users</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-red-500 mr-2">error</i>
                <p class="text-red-600 dark:text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->profile && $user->profile->avatar)
                                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->name }}" 
                                                 class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-600 flex items-center justify-center">
                                                <span class="text-slate-600 dark:text-slate-300 text-lg font-medium">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->contactDetails)
                                    <div class="text-sm text-slate-900 dark:text-white">
                                        {{ $user->contactDetails->phone }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        {{ $user->contactDetails->country }}
                                    </div>
                                @else
                                    <span class="text-sm text-slate-500 dark:text-slate-400">No contact details</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->email_verified_at ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                       title="View User">
                                        <i class="material-symbols-outlined">visibility</i>
                                    </a>
                                    <form action="{{ route('admin.users.login-as', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300"
                                                title="Login as User"
                                                onclick="return confirm('Are you sure you want to login as {{ $user->name }}?')">
                                            <i class="material-symbols-outlined">login</i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 