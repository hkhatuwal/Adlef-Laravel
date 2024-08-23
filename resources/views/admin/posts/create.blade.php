@extends('admin._partials.admin_main')

@section('content')
        <div class="w-full xl:w-1/2">
            <h1 class="text-3xl text-gray-900 mb-8 ">Create a Post</h1>
            <form action="{{route('admin.posts.store')}}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <!-- Title -->
                <div>
                    <label for="title" class="block text-lg font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-lg" required>
                </div>

                <!-- Subtitle -->
                <div>
                    <label for="subtitle" class="block text-lg font-medium text-gray-700">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-lg">
                </div>

                <!-- Label -->
                <div>
                    <label for="label" class="block text-lg font-medium text-gray-700">Label</label>
                    <input type="text" name="label" id="label" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-lg">
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-lg font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-lg" rows="6" required></textarea>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-lg font-medium text-gray-700">Type</label>
                    <select name="category_id" id="category_id" onchange="showLinksInputs(this)" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-lg" required>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="links">
                    <div>
                        <label for="apple_link" class="block text-sm font-medium text-gray-700">Apple Link</label>
                        <input type="url" name="apple_link" id="apple_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="spotify_link" class="block text-sm font-medium text-gray-700">Spotify Link</label>
                        <input type="url" name="spotify_link" id="spotify_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="google_link" class="block text-sm font-medium text-gray-700">Google Link</label>
                        <input type="url" name="google_link" id="google_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="amazon_link" class="block text-sm font-medium text-gray-700">Amazon Link</label>
                        <input type="url" name="amazon_link" id="amazon_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <!-- Image Picker -->
                <div>
                    <label for="image" class="block text-lg font-medium text-gray-700">Upload Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-2 block w-full text-lg text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" onchange="previewImage(event)">
                    <div class="mt-4">
                        <img id="image-preview" class="w-full h-auto rounded-lg shadow-md" style="display: none;" />
                    </div>
                </div>
                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-transform transform hover:scale-105">
                        Submit
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
@endsection
