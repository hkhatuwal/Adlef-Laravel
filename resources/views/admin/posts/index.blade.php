@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Posts Management</h2>
                <p class="mt-1 text-sm text-slate-600">Manage and organize your blog posts</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.posts.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <i class="material-symbols-outlined mr-2">add_circle</i>
                    Create New Post
                </a>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl  border border-slate-200 p-1">
        <div class="overflow-x-auto">
            <table id="default-table" class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 mr-2">
                                    <i class="material-symbols-outlined text-base">title</i>
                                </span>
                                Title
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 mr-2">
                                    <i class="material-symbols-outlined text-base">short_text</i>
                                </span>
                                Subtitle
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 mr-2">
                                    <i class="material-symbols-outlined text-base">category</i>
                                </span>
                                Category
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 mr-2">
                                    <i class="material-symbols-outlined text-base">article</i>
                                </span>
                                Content
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 mr-2">
                                    <i class="material-symbols-outlined text-base">image</i>
                                </span>
                                Image
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-600 mr-2">
                                    <i class="material-symbols-outlined text-base">settings</i>
                                </span>
                                Actions
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-3 
                                        {{ $post->category_id == 1 ? 'bg-indigo-50 text-indigo-600' : 
                                        ($post->category_id == 2 ? 'bg-emerald-50 text-emerald-600' : 
                                        ($post->category_id == 3 ? 'bg-purple-50 text-purple-600' : 
                                        'bg-blue-50 text-blue-600')) }}">
                                        <i class="material-symbols-outlined">
                                            {{ $post->category_id == 1 ? 'headphones' : 
                                            ($post->category_id == 2 ? 'article' : 
                                            ($post->category_id == 3 ? 'music_note' : 
                                            'description')) }}
                                        </i>
                                    </span>
                                    <div class="text-sm font-medium text-slate-900">{{ $post->title }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $post->subtitle }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium 
                                    {{ $post->category_id == 1 ? 'bg-indigo-50 text-indigo-600' : 
                                    ($post->category_id == 2 ? 'bg-emerald-50 text-emerald-600' : 
                                    ($post->category_id == 3 ? 'bg-purple-50 text-purple-600' : 
                                    'bg-blue-50 text-blue-600')) }}">
                                    <i class="material-symbols-outlined text-base mr-1">
                                        {{ $post->category_id == 1 ? 'headphones' : 
                                        ($post->category_id == 2 ? 'article' : 
                                        ($post->category_id == 3 ? 'music_note' : 
                                        'description')) }}
                                    </i>
                                    {{ $post->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 max-w-xs truncate">
                                    {{ Str::limit($post->content, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}"
                                         alt="Post Image"
                                         class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                                @else
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-amber-50 text-amber-400">
                                        <i class="material-symbols-outlined">image_not_supported</i>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="material-symbols-outlined mr-1.5 text-indigo-500">edit</i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete(this.form)"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-200 rounded-lg text-sm font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                            <i class="material-symbols-outlined mr-1.5 text-red-500">delete</i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="material-symbols-outlined text-2xl text-slate-400">article</i>
                                    </div>
                                    <h3 class="text-sm font-medium text-slate-900 mb-1">No Posts Found</h3>
                                    <p class="text-sm text-slate-500">Get started by creating a new post.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script>
$(document).ready(function() {
    const dataTable = new simpleDatatables.DataTable("#default-table", {
        searchable: true,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25],
        labels: {
            placeholder: "Search posts...",
            perPage: "Posts per page",
            noRows: "No posts found",
            info: "Showing {start} to {end} of {rows} posts",
        },
    });
});

function confirmDelete(form) {
    if (confirm("Are you sure you want to delete this post? This action cannot be undone.")) {
        form.submit();
    }
}
</script>
@endsection
