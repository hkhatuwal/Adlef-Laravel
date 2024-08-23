@extends('admin._partials.admin_main')

@section('content')
    <div class="w-1/2 ">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Edit Post</h1>
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use PUT method for updates -->

            <!-- Title -->
            <div>
                <label for="title" class="block text-lg font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 text-lg" required>
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-lg font-medium text-gray-700">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $post->subtitle) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 text-lg">
            </div>

            <!-- Label -->
            <div>
                <label for="label" class="block text-lg font-medium text-gray-700">Label</label>
                <input type="text" name="label" id="label" value="{{ old('label', $post->label) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 text-lg">
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-lg font-medium text-gray-700">Content</label>
                <textarea name="content" id="content" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 text-lg" rows="6" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <!-- Category Type -->
            <div>
                <label for="category_id" class="block text-lg font-medium text-gray-700">Category</label>
                <select onchange="showLinksInputs(this)" name="category_id" id="category_id" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 text-lg" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Image Picker -->
            <div>
                <label for="image" class="block text-lg font-medium text-gray-700" >Upload Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-2 block w-full text-lg text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3" onchange="previewImage(event)">
                <div class="mt-4">
                    <img id="image-preview" class="w-full h-auto rounded-lg shadow-md" style="display: {{ $post->image ? 'block' : 'none' }};" src="{{ $post->image ? asset('storage/' . $post->image) : '' }}" />
                </div>
            </div>
            <!-- Links -->

            <div class="links">
                <div>
                    <label for="apple_link" class="block text-sm font-medium text-gray-700">Apple Link</label>
                    <input type="url" value="{{$post->links['apple']}}" name="apple_link" id="apple_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="spotify_link" class="block text-sm font-medium text-gray-700">Spotify Link</label>
                    <input value="{{$post->links['spotify']}}" type="url"  name="spotify_link" id="spotify_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="google_link" class="block text-sm font-medium text-gray-700">Google Link</label>
                    <input value="{{$post->links['google']}}" type="url" name="google_link" id="google_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="amazon_link" class="block text-sm font-medium text-gray-700">Amazon Link</label>
                    <input value="{{$post->links['amazon']}}"  type="url" name="amazon_link" id="amazon_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>


            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-transform transform hover:scale-105">
                    Update Post
                </button>
            </div>
        </form>
    </div>
@endsection

@section('post-script')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('image-preview');
            reader.onload = function() {
                if (reader.readyState === 2) {
                    imagePreview.src = reader.result;
                    imagePreview.style.display = 'block';
                }
            }

            reader.readAsDataURL(event.target.files[0]);
        }
        function showLinksInputs(event) {
            var PODCAST_ID=1;
            if(event.value==PODCAST_ID){
                $('.links').removeClass('hidden')
            }
            else {
                $('.links').addClass('hidden')

            }
        }
    </script>
    </script>
@endsection
