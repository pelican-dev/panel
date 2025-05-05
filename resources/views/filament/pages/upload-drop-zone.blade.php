{{-- Upload Progress Popup --}}
<div id="upload-status-popup"
     class="hidden fixed bottom-4 right-4 bg-white shadow-xl border border-gray-300 rounded-xl p-4 w-72 z-50">
    <h3 class="font-semibold mb-2 text-gray-700">Uploading...</h3>
    <div id="upload-progress-list" class="space-y-2 text-sm text-gray-600"></div>
</div>

{{-- Hidden File Input for FilePond --}}
<input type="file" id="hidden-uploader" name="files[]" multiple>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.7/filepond.min.js" integrity="sha512-/kcyaB84QgojPP9E91b3lmeLZXKMG6wGqIGaTIld4RtbyTBFB0DHBSL7/WPwU/fAftwmpvYYvdq/S96smP8Ppw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.7/filepond.min.css" integrity="sha512-FJiY0+cfDomcgiTe/XuOgtE7QW6R0NGnCDonCOgVztYK7+USa0Y5a3LoUcXUhfCjDq9oJ91hqyThq2eN69OZBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('upload-status-popup');
            const progressList = document.getElementById('upload-progress-list');
            const fileInput = document.getElementById('hidden-uploader');

            // Initialize FilePond
            const pond = FilePond.create(fileInput, {
                credits: false,
                allowMultiple: true,
                dropOnPage: true,  // Enable drop anywhere on the page
                dropValidation: true,
                server: {
                    process: {
                        url: '/upload',
                        method: 'POST',
                        load: (response) => {
                            console.log('File uploaded successfully:', response);
                            return response;
                        },
                        onerror: (error) => {
                            console.error('Error uploading file:', error);
                            return error;
                        }
                    }
                },
                onaddfilestart: (file) => {
                    showPopup();
                    addProgressItem(file);
                },
                onprocessfileprogress: (file, progress) => {
                    updateProgressItem(file, progress);
                },
                onprocessfile: (file) => {
                    updateProgressItem(file, 1);
                },
                onprocessfiles: () => {
                    setTimeout(hidePopup, 1500);
                }
            });

            // Handle dragover on the entire page (prevents default behavior)
            document.body.addEventListener('dragover', (e) => {
                e.preventDefault();  // Prevent default behavior to allow drop
                console.log("dragover event triggered");  // Debugging
                document.body.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');  // Optional: visual feedback
            });

            // Handle dragleave event
            document.body.addEventListener('dragleave', () => {
                document.body.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
            });

            // Handle drop event on the entire page (ensure default behavior is prevented)
            document.body.addEventListener('drop', (e) => {
                e.preventDefault();  // Prevent the default drop behavior
                console.log("drop event triggered");  // Debugging

                document.body.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');

                // Get dropped files
                const files = Array.from(e.dataTransfer.files || []);  // Convert FileList to an array
                console.log('Dropped files:', files);  // Debugging

                // Ensure there are files to add
                if (files.length > 0) {
                    // Add files to FilePond
                    pond.addFiles(files).then(() => {
                        console.log('Files successfully added to FilePond');
                    }).catch((error) => {
                        console.error('Error adding files to FilePond:', error);
                    });
                } else {
                    console.warn('No files dropped');
                }
            });

            // Show the upload progress popup
            function showPopup() {
                popup.classList.remove('hidden');
            }

            // Hide the upload progress popup
            function hidePopup() {
                popup.classList.add('hidden');
                progressList.innerHTML = '';
            }

            // Add a new progress item to the list
            function addProgressItem(file) {
                const id = file.id;
                const div = document.createElement('div');
                div.id = `progress-${id}`;
                div.innerHTML = `
                    <div class="flex justify-between">
                        <span class="truncate w-40">${file.filename}</span>
                        <span class="upload-progress">0%</span>
                    </div>
                    <div class="h-1 bg-gray-200 rounded">
                        <div class="h-1 bg-blue-500 rounded progress-bar" style="width: 0%"></div>
                    </div>
                `;
                progressList.appendChild(div);
            }

            // Update the progress of a file
            function updateProgressItem(file, progress) {
                const id = file.id;
                const el = document.getElementById(`progress-${id}`);
                if (el) {
                    const percent = Math.round(progress * 100);
                    el.querySelector('.upload-progress').textContent = `${percent}%`;
                    el.querySelector('.progress-bar').style.width = `${percent}%`;
                }
            }
        });
    </script>
@endpush
