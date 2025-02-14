@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Edit Post</h2>
                <p class="mt-1 text-sm text-slate-600">Update your existing post</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="material-symbols-outlined mr-2">arrow_back</i>
                Back to Posts
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-3xl">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <!-- Main Content Section -->
                <div class="p-6 space-y-6">
                    <!-- Title & Subtitle Group -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700">
                                <span class="flex items-center">
                                    <i class="material-symbols-outlined mr-2 text-slate-400">title</i>
                                    Title
                                </span>
                            </label>
                            <input type="text" name="title" id="title" 
                                value="{{ old('title', $post->title) }}"
                                class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                placeholder="Enter post title" required>
                        </div>

                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-slate-700">
                                <span class="flex items-center">
                                    <i class="material-symbols-outlined mr-2 text-slate-400">short_text</i>
                                    Subtitle
                                </span>
                            </label>
                            <input type="text" name="subtitle" id="subtitle" 
                                value="{{ old('subtitle', $post->subtitle) }}"
                                class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                placeholder="Enter post subtitle">
                        </div>
                    </div>

                    <!-- Label & Category Group -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="label" class="block text-sm font-medium text-slate-700">
                                <span class="flex items-center">
                                    <i class="material-symbols-outlined mr-2 text-slate-400">label</i>
                                    Label
                                </span>
                            </label>
                            <input type="text" name="label" id="label" 
                                value="{{ old('label', $post->label) }}"
                                class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                placeholder="Enter post label">
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-700">
                                <span class="flex items-center">
                                    <i class="material-symbols-outlined mr-2 text-slate-400">category</i>
                                    Category
                                </span>
                            </label>
                            <select name="category_id" id="category_id" onchange="showLinksInputs(this)" 
                                class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-slate-700">
                            <span class="flex items-center">
                                <i class="material-symbols-outlined mr-2 text-slate-400">article</i>
                                Content
                            </span>
                        </label>
                        <textarea name="content" id="content" rows="6" 
                            class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                            placeholder="Write your post content here..." required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    <!-- Links Section -->
                    <div class="links {{ $post->category_id != 1 ? 'hidden' : '' }} space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="apple_link" class="block text-sm font-medium text-slate-700">
                                    <span class="flex items-center">
                                        <i class="fab fa-apple mr-2 text-slate-400"></i>
                                        Apple Music Link
                                    </span>
                                </label>
                                <input type="url" name="apple_link" id="apple_link" 
                                    value="{{ $post->links['apple'] ?? '' }}"
                                    class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                    placeholder="https://music.apple.com/...">
                            </div>

                            <div>
                                <label for="spotify_link" class="block text-sm font-medium text-slate-700">
                                    <span class="flex items-center">
                                        <i class="fab fa-spotify mr-2 text-slate-400"></i>
                                        Spotify Link
                                    </span>
                                </label>
                                <input type="url" name="spotify_link" id="spotify_link" 
                                    value="{{ $post->links['spotify'] ?? '' }}"
                                    class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                    placeholder="https://open.spotify.com/...">
                            </div>

                            <div>
                                <label for="google_link" class="block text-sm font-medium text-slate-700">
                                    <span class="flex items-center">
                                        <i class="fab fa-google-play mr-2 text-slate-400"></i>
                                        Google Play Link
                                    </span>
                                </label>
                                <input type="url" name="google_link" id="google_link" 
                                    value="{{ $post->links['google'] ?? '' }}"
                                    class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                    placeholder="https://play.google.com/...">
                            </div>

                            <div>
                                <label for="amazon_link" class="block text-sm font-medium text-slate-700">
                                    <span class="flex items-center">
                                        <i class="fab fa-amazon mr-2 text-slate-400"></i>
                                        Amazon Music Link
                                    </span>
                                </label>
                                <input type="url" name="amazon_link" id="amazon_link" 
                                    value="{{ $post->links['amazon'] ?? '' }}"
                                    class="mt-2 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors" 
                                    placeholder="https://music.amazon.com/...">
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-slate-700">
                            <span class="flex items-center">
                                <i class="material-symbols-outlined mr-2 text-slate-400">image</i>
                                Featured Image
                            </span>
                        </label>
                        <div class="mt-2">
                            <div class="flex items-center justify-center w-full">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-200 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-text" {!! $post->image ? 'style="display: none;"' : '' !!}>
                                        <i class="material-symbols-outlined text-4xl text-slate-400 mb-4">cloud_upload</i>
                                        <p class="mb-2 text-sm text-slate-600">
                                            <span class="font-medium">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-slate-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                    </div>
                                    <img id="image-preview" 
                                         src="{{ $post->image ? asset('storage/' . $post->image) : '' }}" 
                                         class="{{ !$post->image ? 'hidden' : '' }} w-full h-full object-cover rounded-lg" />
                                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between">
                    <button type="button" onclick="window.history.back()" 
                        class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                        Cancel
                    </button>
                    <div class="flex space-x-3">
                        <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Update Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('post-script')
<script>
function previewImage(event) {
    const reader = new FileReader();
    const imagePreview = document.getElementById('image-preview');
    const uploadText = document.getElementById('upload-text');
    
    reader.onload = function() {
        if (reader.readyState === 2) {
            imagePreview.src = reader.result;
            imagePreview.classList.remove('hidden');
            uploadText.style.display = 'none';
        }
    }
    
    reader.readAsDataURL(event.target.files[0]);
}

function showLinksInputs(select) {
    const PODCAST_ID = 1;
    const linksSection = document.querySelector('.links');
    
    if (parseInt(select.value) === PODCAST_ID) {
        linksSection.classList.remove('hidden');
    } else {
        linksSection.classList.add('hidden');
    }
}

// Initialize links section visibility based on category
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    showLinksInputs(categorySelect);
});
</script>
@endsection
