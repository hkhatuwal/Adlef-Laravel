@extends('admin._partials.admin_main')

@section('title', 'Staff Management')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Staff Management</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage staff members and their access</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="material-symbols-outlined mr-2">person_add</i>
                Add New Staff
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                <div class="flex items-center">
                    <i class="material-symbols-outlined mr-2">check_circle</i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
            <div class="p-1 md:p-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <i class="material-symbols-outlined text-base">badge</i>
                                        <span>Staff Details</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <i class="material-symbols-outlined text-base">schedule</i>
                                        <span>Created</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <i class="material-symbols-outlined text-base">verified</i>
                                        <span>Status</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($staffMembers as $staff)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                                                    <span class="text-indigo-600 dark:text-indigo-400 text-lg font-medium">
                                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $staff->name }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $staff->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $staff->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $staff->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400">
                                            <i class="material-symbols-outlined text-base mr-1">check_circle</i>
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('admin.staff.edit', $staff->id) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center">
                                                <i class="material-symbols-outlined text-base">edit</i>
                                                <span class="ml-1">Edit</span>
                                            </a>
                                            <button type="button"
                                                    onclick="confirmDelete({{ $staff->id }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                                                <i class="material-symbols-outlined text-base">delete</i>
                                                <span class="ml-1">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">
                    {{ $staffMembers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="material-symbols-outlined text-red-600 dark:text-red-400">warning</i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white" id="modal-title">
                                Delete Staff Member
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Are you sure you want to delete this staff member? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(staffId) {
            const modal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/admin/staff/${staffId}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }
    </script>
@endsection
