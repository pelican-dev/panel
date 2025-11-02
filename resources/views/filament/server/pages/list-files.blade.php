<x-filament-panels::page>
    <div
        x-data="{
            isDragging: false,
            dragCounter: 0,
            isUploading: false,
            uploadQueue: [],
            currentFileIndex: 0,
            totalFiles: 0,
            autoCloseTimer: null,
            handleDragEnter(e) {
                e.preventDefault();
                e.stopPropagation();
                this.dragCounter++;
                this.isDragging = true;
            },
            handleDragLeave(e) {
                e.preventDefault();
                e.stopPropagation();
                this.dragCounter--;
                if (this.dragCounter === 0) {
                    this.isDragging = false;
                }
            },
            handleDragOver(e) {
                e.preventDefault();
                e.stopPropagation();
            },
            async handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                this.isDragging = false;
                this.dragCounter = 0;

                const files = e.dataTransfer.files;
                if (files.length === 0) return;

                await this.uploadFiles(files);
            },
            async uploadFiles(files) {
                this.isUploading = true;
                this.uploadQueue = [];
                this.totalFiles = files.length;
                this.currentFileIndex = 0;

                try {
                    const uploadSizeLimit = await $wire.getUploadSizeLimit();

                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > uploadSizeLimit) {
                            new window.FilamentNotification()
                                .title(`File ${files[i].name} exceeds the upload size limit of ${this.formatBytes(uploadSizeLimit)}`)
                                .danger()
                                .send();
                            this.isUploading = false;
                            return;
                        }
                    }

                    for (let i = 0; i < files.length; i++) {
                        this.uploadQueue.push({
                            file: files[i],
                            name: files[i].name,
                            size: files[i].size,
                            progress: 0,
                            speed: 0,
                            uploadedBytes: 0,
                            status: 'pending',
                            error: null
                        });
                    }

                    const maxConcurrent = 3;
                    let activeUploads = [];
                    let completedCount = 0;

                    for (let i = 0; i < files.length; i++) {
                        const uploadPromise = this.uploadFile(i)
                            .then(() => {
                                completedCount++;
                                this.currentFileIndex = completedCount;
                            })
                            .catch((error) => {
                                completedCount++;
                                this.currentFileIndex = completedCount;
                            });

                        activeUploads.push(uploadPromise);

                        if (activeUploads.length >= maxConcurrent) {
                            await Promise.race(activeUploads);
                            activeUploads = activeUploads.filter(p => {
                                let isPending = true;
                                p.then(() => { isPending = false; }).catch(() => { isPending = false; });
                                return isPending;
                            });
                        }
                    }

                    await Promise.allSettled(activeUploads);
                    const failedUploads = this.uploadQueue.filter(f => f.status === 'error');
                    await $wire.$refresh();

                    if (failedUploads.length === 0) {
                        new window.FilamentNotification()
                            .title('{{ trans('server/file.actions.upload.success') }}')
                            .success()
                            .send();
                    } else if (failedUploads.length < this.totalFiles) {
                        new window.FilamentNotification()
                            .title(`${this.totalFiles - failedUploads.length} of ${this.totalFiles} files uploaded successfully`)
                            .warning()
                            .send();
                    } else {
                        new window.FilamentNotification()
                            .title('{{ trans('server/file.actions.upload.failed') }}')
                            .danger()
                            .send();
                    }

                } catch (error) {
                    new window.FilamentNotification()
                        .title('{{ trans('server/file.actions.upload.failed') }}')
                        .danger()
                        .send();
                } finally {
                    this.closeUploadDialog();
                }
            },
            async uploadFile(index) {
                const fileData = this.uploadQueue[index];
                fileData.status = 'uploading';

                try {
                    const uploadUrl = await $wire.getUploadUrl();
                    const url = new URL(uploadUrl);
                    url.searchParams.append('directory', @js($this->path));

                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        const formData = new FormData();
                        formData.append('files', fileData.file);

                        let lastLoaded = 0;
                        let lastTime = Date.now();

                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                fileData.uploadedBytes = e.loaded;
                                fileData.progress = Math.round((e.loaded / e.total) * 100);

                                // Calculate upload speed
                                const currentTime = Date.now();
                                const timeDiff = (currentTime - lastTime) / 1000;
                                if (timeDiff > 0.1) {
                                    const bytesDiff = e.loaded - lastLoaded;
                                    fileData.speed = bytesDiff / timeDiff;
                                    lastTime = currentTime;
                                    lastLoaded = e.loaded;
                                }
                            }
                        });

                        xhr.addEventListener('load', () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                fileData.status = 'complete';
                                fileData.progress = 100;
                                resolve();
                            } else {
                                fileData.status = 'error';
                                fileData.error = `Upload failed (${xhr.status})`;
                                reject(new Error(fileData.error));
                            }
                        });

                        xhr.addEventListener('error', () => {
                            fileData.status = 'error';
                            fileData.error = 'Network error';
                            reject(new Error('Upload failed'));
                        });

                        xhr.open('POST', url.toString());
                        xhr.send(formData);
                    });
                } catch (error) {
                    fileData.status = 'error';
                    fileData.error = 'Failed to get upload token';
                    throw error;
                }
            },
            formatBytes(bytes) {
                if (bytes === 0) return '0.00 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return (bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i];
            },
            formatSpeed(bytesPerSecond) {
                return this.formatBytes(bytesPerSecond) + '/s';
            },
            closeUploadDialog() {
                if (this.autoCloseTimer) {
                    clearTimeout(this.autoCloseTimer);
                    this.autoCloseTimer = null;
                }
                this.isUploading = false;
                this.uploadQueue = [];
            },
            handleEscapeKey(e) {
                if (e.key === 'Escape' && this.isUploading) {
                    this.closeUploadDialog();
                }
            }
        }"
        @dragenter.window="handleDragEnter($event)"
        @dragleave.window="handleDragLeave($event)"
        @dragover.window="handleDragOver($event)" s
        @drop.window="handleDrop($event)"
        @keydown.window="handleEscapeKey($event)"
        class="relative"
    >
        <!-- Drag & Drop Overlay -->
        <div
            x-show="isDragging"
            x-cloak
            x-transition:enter="transition-[opacity] duration-200 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-[opacity] duration-150 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 dark:bg-gray-100/20"
        >
            <div class="rounded-lg bg-white p-8 shadow-xl dark:bg-gray-800">
                <div class="flex flex-col items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-upload size-12 text-success-500"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 9l5 -5l5 5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ trans('server/file.actions.upload.drop_files') }}
                    </p>
                </div>
            </div>
        </div>

        <div
            x-show="isUploading"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 dark:bg-gray-100/20 p-4"
        >
            <div
                class="rounded-lg bg-white shadow-xl dark:bg-gray-800 w-1/2 max-h-[50vh] overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ trans('server/file.actions.upload.header') }} -
                        <span class="text-lg text-gray-600 dark:text-gray-400">
                            <span x-text="currentFileIndex"></span> Of <span x-text="totalFiles"></span>
                        </span>
                    </h3>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div class="overflow-hidden">
                        <table class="w-full divide-y divide-gray-200 dark:divide-white/5">
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5 bg-white dark:bg-gray-900">
                            <template x-for="(fileData, index) in uploadQueue" :key="index">
                                <tr class="transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                    <td class="px-4 py-4 sm:px-6">
                                        <div class="flex flex-col gap-y-1">
                                            <div
                                                class="text-sm font-medium leading-6 text-gray-950 dark:text-white truncate max-w-xs"
                                                x-text="fileData.name"></div>
                                            <div x-show="fileData.status === 'error'"
                                                 class="text-xs text-danger-600 dark:text-danger-400"
                                                 x-text="fileData.error"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 sm:px-6">
                                        <div class="text-sm text-gray-500 dark:text-gray-400"
                                             x-text="formatBytes(fileData.size)"></div>
                                    </td>
                                    <td class="px-4 py-4 sm:px-6">
                                        <div x-show="fileData.status === 'uploading' || fileData.status === 'complete'"
                                             class="flex justify-between items-center text-sm">
                                            <span class="font-medium text-gray-700 dark:text-gray-300"
                                                  x-text="`${fileData.progress}%`"></span>
                                            <span x-show="fileData.status === 'uploading' && fileData.speed > 0"
                                                  class="text-gray-500 dark:text-gray-400"
                                                  x-text="formatSpeed(fileData.speed)"></span>
                                        </div>
                                        <span x-show="fileData.status === 'pending'"
                                              class="text-sm text-gray-500 dark:text-gray-400">
                                            —
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{ $this->table }}
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
