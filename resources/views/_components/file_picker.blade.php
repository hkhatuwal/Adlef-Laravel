<label for="{{$id}}" class="block cursor-pointer" id="parent-{{$id}}">
    <div
        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-lg transition-all duration-200 group"
        id="drop-zone"
        ondrop="dropHandler(event);"
        ondragover="dragOverHandler(event);"
        ondragleave="dragLeaveHandler(event);">
        <div class="space-y-1 text-center">
            <input type="hidden" name="{{$id."_path"}}" id="{{$id."_path"}}" >
            <div id="upload-icon" class="transition-transform group-hover:scale-110 duration-200">
                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-gray-500" stroke="currentColor" fill="none"
                     viewBox="0 0 48 48">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div id="success-icon" class="hidden">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-sm text-gray-600">
                <span class="font-medium text-gray-600 group-hover:text-gray-800 transition-colors">Upload a file</span>
                <span class="pl-1">or drag and drop</span>
            </div>
            <input id="{{$id}}" name="{{$id}}" type="file" class="sr-only" accept=".jpg,.png,.pdf" required
                   onclick="this.value=null;">
            <p class="text-xs text-gray-500">JPG, PNG, PDF up to 25MB</p>
            <p id="file-name" class="text-sm text-gray-800 mt-2 font-bold"></p>
        </div>

    </div>
    <div class="upload-progress hidden mt-2">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-primary h-2.5 rounded-full progress-bar" style="width: 0%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-1 progress-text">0%</p>
    </div>
</label>
<p class="mt-1 text-sm text-red-600 hidden"></p>


<script>
    function updateFileUploadUI(fileName) {
        console.log(fileName)
        console.log('#{{$id}} #upload-icon')
        if (fileName) {
            $('#parent-{{$id}} #upload-icon').addClass('hidden');
            $('#parent-{{$id}} #success-icon').removeClass('hidden');
            $('#parent-{{$id}} #file-name').text(fileName);
            $('#parent-{{$id}} #drop-zone').addClass('border-green-500 bg-green-50').removeClass('group');
        } else {
            $('#parent-{{$id}} #upload-icon').removeClass('hidden');
            $('#parent-{{$id}} #success-icon').addClass('hidden');
            $('#parent-{{$id}} #file-name').text('');
            $('#parent-{{$id}} #drop-zone').removeClass('border-green-500 bg-green-50').addClass('group');
        }
    }

    function dragOverHandler(event) {
        event.preventDefault();
        event.stopPropagation();
        if (!$('#success-icon').is(':visible')) {
            $('#drop-zone').addClass('border-gray-400 bg-gray-50');
        }
    }

    function dragLeaveHandler(event) {
        event.preventDefault();
        event.stopPropagation();
        if (!$('#success-icon').is(':visible')) {
            $('#drop-zone').removeClass('border-gray-400 bg-gray-50');
        }
    }

    function dropHandler(event) {
        event.preventDefault();
        event.stopPropagation();

        $('#parent-{{$id}} #drop-zone').removeClass('border-gray-400 bg-gray-50');

        const dt = event.dataTransfer;
        const files = dt.files;

        handleFileSelection(files);
    }

    function handleFileSelection(files) {
        if (files.length > 0) {
            const file = files[0];
            const allowedTypes = ['.jpg', '.jpeg', '.png', '.pdf'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

            if (allowedTypes.includes(fileExtension)) {
                if (file.size <= 25 * 1024 * 1024) { // 25MB in bytes
                    const input = document.getElementById('{{$id}}');

                    // Create a new FileList object
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    uploadDocument(file)
                    updateFileUploadUI(file.name);
                } else {
                    alert('File size exceeds 25MB limit. Please select a smaller file.');
                    resetFileUpload();
                }
            } else {
                alert('Invalid file type. Please upload a JPG, PNG, or PDF file.');
                resetFileUpload();
            }
        }
    }

    function resetFileUpload() {
        const input = document.getElementById('{{$id}}');
        input.value = '';
        updateFileUploadUI('');
    }

    function uploadDocument(file) {
        const uploadProgress = $('.upload-progress');
        const progressBar = $('.progress-bar');
        const progressText = $('.progress-text');


        const formData = new FormData();
        formData.append('document', file);


        uploadProgress.removeClass('hidden');
        progressBar.css('width', '0%');
        progressText.text('0%');

        $.ajax({
            url: '{{route('client.document.upload-document')}}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.css('width', percent + '%');
                        progressText.text(percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                if (response.status === 'success') {
                    progressBar.css('width', '100%');
                    progressText.text('100%');
                    $('#file-upload').addClass('opacity-50 pointer-events-none');
                    $('#{{$id}}_path').val(response.path);
                } else {
                    toastr.error(response.message || 'Failed to upload document');
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error('Failed to upload document');
                uploadProgress.addClass('hidden');
            }
        });
    }


    window.onload = function () {
        $('#{{$id}}').on('change', function (e) {
            console.log("change")
            if (e.target.files.length > 0) {
                handleFileSelection(e.target.files);
            } else {
                resetFileUpload();
            }
        });
    }    </script>
