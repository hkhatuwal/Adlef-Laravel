@extends('admin._partials.admin_main')

@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Help Center Categories</h1>
                    <p class="mt-2 text-base text-slate-600 dark:text-slate-400">Manage categories for help requests</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.help-center.index') }}"
                       class="inline-flex items-center justify-center bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="material-symbols-outlined mr-2 text-lg">arrow_back</i>
                        Back to Requests
                    </a>
                    <button id="openCreateModel"
                            class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="material-symbols-outlined mr-2 text-lg">add</i>
                        Add Category
                    </button>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-400 rounded-r-lg shadow-sm">
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

            <!-- Categories Table -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Categories</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Organize help requests by category</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Sort Order
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Requests
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors duration-150">
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                                <i class="material-symbols-outlined text-white text-lg">category</i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                                {{ $category->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm text-slate-600 dark:text-slate-400 max-w-xs">
                                        {{ Str::limit($category->description, 100) ?: 'No description provided' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200">
                                        {{ $category->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-2 {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-2xl font-bold text-slate-900 dark:text-white">{{ $category->helpRequests->count() }}</span>
                                        <span class="text-sm text-slate-500 dark:text-slate-400">requests</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button
                                            class="edit-category-btn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 transition-colors duration-150"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            data-active="{{ $category->is_active ? 'true' : 'false' }}"
                                            data-sort="{{ $category->sort_order }}"
                                            title="Edit category">
                                            <i class="material-symbols-outlined text-lg">edit</i>
                                        </button>
                                        @if($category->helpRequests->count() == 0)
                                            <form
                                                action="{{ route('admin.help-center.categories.destroy', $category) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 transition-colors duration-150"
                                                        title="Delete category">
                                                    <i class="material-symbols-outlined text-lg">delete</i>
                                                </button>
                                            </form>
                                        @else
                                            <div
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500"
                                                title="Cannot delete category with existing requests">
                                                <i class="material-symbols-outlined text-lg">lock</i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div
                                            class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-2xl">category</i>
                                        </div>
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <p class="text-lg font-medium">No categories found</p>
                                            <p class="text-sm">Create your first category to organize help requests.</p>
                                        </div>
                                        <button id="createModal"
                                                class="mt-4 inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                            <i class="material-symbols-outlined mr-2">add</i>
                                            Add Category
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div id="createModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md">
            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl mx-4">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Create New Category</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Add a new category for organizing help
                        requests</p>
                </div>
                <form action="{{ route('admin.help-center.categories.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="create_name"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category
                            Name</label>
                        <input type="text" name="name" id="create_name" required
                               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors"
                               placeholder="Enter category name">
                    </div>
                    <div>
                        <label for="create_description"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea name="description" id="create_description" rows="3"
                                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors"
                                  placeholder="Describe what this category is for"></textarea>
                    </div>
                    <div>
                        <label for="create_sort_order"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sort
                            Order</label>
                        <input type="number" name="sort_order" id="create_sort_order" value="0" min="0"
                               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors">
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeCreateModal()"
                                class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md">
            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl mx-4">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Edit Category</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update category information</p>
                </div>
                <form id="editForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit_name"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category
                            Name</label>
                        <input type="text" name="name" id="edit_name" required
                               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors">
                    </div>
                    <div>
                        <label for="edit_description"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors"></textarea>
                    </div>
                    <div>
                        <label for="edit_sort_order"
                               class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sort
                            Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" min="0"
                               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-colors">
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                                class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
@section('post-script')
    <script src="{{asset('admin/js/help-center/script.js')}}"></script>
@endsection
