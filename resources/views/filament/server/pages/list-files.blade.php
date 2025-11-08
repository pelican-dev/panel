<x-filament-panels::page>
    <div
        x-data="
        {
            isDragging: false,
            dragCounter: 0,
            isUploading: false,
            uploadQueue: [],
            currentFileIndex: 0,
            totalFiles: 0,
            autoCloseTimer: 1000,

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
                if (this.dragCounter === 0) this.isDragging = false;
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

                const items = e.dataTransfer.items;
                const files = e.dataTransfer.files;

                if ((!items || items.length === 0) && (!files || files.length === 0)) return;

                let filesWithPaths = [];

                if (items && items.length > 0 && items[0].webkitGetAsEntry) {
                    filesWithPaths = await this.extractFilesFromItems(items);
                }

                if (files && files.length > 0 && filesWithPaths.length === 0) {
                    filesWithPaths = Array.from(files).map(f => ({
                        file: f,
                        path: ''
                    }));
                }

                if (filesWithPaths.length > 0) {
                    await this.uploadFilesWithFolders(filesWithPaths);
                }
            },

            async extractFilesFromItems(items) {
                const filesWithPaths = [];
                const traversePromises = [];

                for (let i = 0; i < items.length; i++) {
                    const entry = items[i].webkitGetAsEntry?.();

                    if (entry) {
                        traversePromises.push(this.traverseFileTree(entry, '', filesWithPaths));
                    } else if (items[i].kind === 'file') {
                        const file = items[i].getAsFile();
                        if (file) {
                            filesWithPaths.push({
                                file: file,
                                path: '',
                            });
                        }
                    }
                }

                await Promise.all(traversePromises);

                return filesWithPaths;
            },

            async traverseFileTree(entry, path, filesWithPaths) {
                return new Promise((resolve) => {
                    if (entry.isFile) {
                        entry.file((file) => {
                            filesWithPaths.push({
                                file: file,
                                path: path,
                            });
                            resolve();
                        });
                    } else if (entry.isDirectory) {
                        const reader = entry.createReader();
                        const readEntries = () => {
                            reader.readEntries(async (entries) => {
                                if (entries.length === 0) {
                                    resolve();
                                    return;
                                }

                                const subPromises = entries.map((e) =>
                                    this.traverseFileTree(
                                        e,
                                        path ? `${path}/${entry.name}` : entry.name,
                                        filesWithPaths
                                    )
                                );

                                await Promise.all(subPromises);
                                readEntries();
                            });
                        };
                        readEntries();
                    } else {
                        resolve();
                    }
                });
            },
            async uploadFilesWithFolders(filesWithPaths) {
                this.isUploading = true;
                this.uploadQueue = [];
                this.totalFiles = filesWithPaths.length;
                this.currentFileIndex = 0;
                const uploadedFiles = [];

                try {
                    const uploadSizeLimit = await $wire.getUploadSizeLimit();

                    for (const {
                            file
                        }
                        of filesWithPaths) {
                        if (file.size > uploadSizeLimit) {
                            new window.FilamentNotification()
                                .title(`File ${file.name} exceeds the upload limit.`)
                                .danger()
                                .send();
                            this.isUploading = false;
                            return;
                        }
                    }

                    const folderPaths = new Set();
                    for (const {
                            path
                        }
                        of filesWithPaths) {
                        if (path) {
                            const parts = path.split('/').filter(Boolean);
                            let currentPath = '';
                            for (const part of parts) {
                                currentPath += part + '/';
                                folderPaths.add(currentPath);
                            }
                        }
                    }

                    for (const folderPath of folderPaths) {
                        try {
                            await $wire.createFolder(folderPath.slice(0, -1));
                        } catch (error) {
                            console.warn(`Folder ${folderPath} already exists or failed to create.`);
                        }
                    }

                    for (const f of filesWithPaths) {
                        this.uploadQueue.push({
                            file: f.file,
                            name: f.file.name,
                            path: f.path,
                            size: f.file.size,
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

                    for (let i = 0; i < this.uploadQueue.length; i++) {
                        const uploadPromise = this.uploadFile(i)
                            .then(() => {
                                completedCount++;
                                this.currentFileIndex = completedCount;
                                const item = this.uploadQueue[i];
                                const relativePath = (item.path ? item.path.replace(/^\/+/, '') + '/' : '') + item.name;
                                uploadedFiles.push(relativePath);
                            })
                            .catch(() => {
                                completedCount++;
                                this.currentFileIndex = completedCount;
                            });

                        activeUploads.push(uploadPromise);

                        if (activeUploads.length >= maxConcurrent) {
                            await Promise.race(activeUploads);
                            activeUploads = activeUploads.filter(p => p.status !== 'fulfilled' && p.status !== 'rejected');
                        }
                    }

                    await Promise.allSettled(activeUploads);

                    const failed = this.uploadQueue.filter(f => f.status === 'error');
                    await $wire.$refresh();

                    if (failed.length === 0) {
                        new window.FilamentNotification()
                            .title('{{ trans('server/file.actions.upload.success') }}')
                            .success()
                            .send();
                    } else if (failed.length === this.totalFiles) {
                        new window.FilamentNotification()
                            .title('{{ trans('server/file.actions.upload.failed') }}')
                            .danger()
                            .send();
                    } else {
                        new window.FilamentNotification()
                            .title('{{ trans('server/file.actions.upload.failed') }}')
                            .danger()
                            .send();
                    }

                    if (uploadedFiles.length > 0) {
                        this.$nextTick(() => {
                            if (typeof $wire !== 'undefined' && $wire && typeof $wire.call === 'function') {
                                $wire.call('logUploadedFiles', uploadedFiles);
                            } else if (typeof window.livewire !== 'undefined' && typeof window.livewire.call === 'function') {
                                window.livewire.call('logUploadedFiles', uploadedFiles);
                            } else if (typeof Livewire !== 'undefined' && typeof Livewire.call === 'function') {
                                Livewire.call('logUploadedFiles', uploadedFiles);
                            } else {
                                console.warn('Could not call Livewire method logUploadedFiles; Livewire not found.');
                            }
                        });
                    }

                    if (this.autoCloseTimer) clearTimeout(this.autoCloseTimer);
                    this.autoCloseTimer = setTimeout(() => {
                        this.isUploading = false;
                        this.uploadQueue = [];
                    }, 1000);
                } catch (error) {
                    console.error('Upload error:', error);
                    new window.FilamentNotification()
                        .title('{{ trans('server/file.actions.upload.error') }}')
                        .danger()
                        .send();
                    this.isUploading = false;
                }
            },

            async uploadFile(index) {
                const fileData = this.uploadQueue[index];
                fileData.status = 'uploading';
                try {
                    const uploadUrl = await $wire.getUploadUrl();
                    const url = new URL(uploadUrl);
                    let basePath = @js($this->path);

                    if (fileData.path && fileData.path.trim() !== '') {
                        basePath = basePath.replace(/\/+$/, '') + '/' + fileData.path.replace(/^\/+/, '');
                    }

                    url.searchParams.append('directory', basePath);

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

                                const now = Date.now();
                                const timeDiff = (now - lastTime) / 1000;
                                if (timeDiff > 0.1) {
                                    const bytesDiff = e.loaded - lastLoaded;
                                    fileData.speed = bytesDiff / timeDiff;
                                    lastTime = now;
                                    lastLoaded = e.loaded;
                                }
                            }
                        });

                        xhr.onload = () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                fileData.status = 'complete';
                                fileData.progress = 100;
                                resolve();
                            } else {
                                fileData.status = 'error';
                                fileData.error = `Upload failed (${xhr.status})`;
                                reject(new Error(fileData.error));
                            }
                        };

                        xhr.onerror = () => {
                            fileData.status = 'error';
                            fileData.error = 'Network error';
                            reject(new Error('Network error'));
                        };

                        xhr.open('POST', url.toString());
                        xhr.send(formData);
                    });
                } catch (err) {
                    fileData.status = 'error';
                    fileData.error = 'Failed to get upload token';
                    throw err;
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
            handleEscapeKey(e) {
                if (e.key === 'Escape' && this.isUploading) {
                    this.isUploading = false;
                    this.uploadQueue = [];
                }
            },
        }"
        @dragenter.window="handleDragEnter($event)"
        @dragleave.window="handleDragLeave($event)"
        @dragover.window="handleDragOver($event)"
        @drop.window="handleDrop($event)"
        @keydown.window="handleEscapeKey($event)"
        class="relative"
    >
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
                         viewBox="0 0 36 36" fill="none"
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
                class="rounded-lg bg-white shadow-xl dark:bg-gray-800 max-w-1/2 max-h-[50vh] overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ trans('server/file.actions.upload.header') }} -
                        <span class="text-lg text-gray-600 dark:text-gray-400">
                            <span x-text="currentFileIndex"></span> of <span x-text="totalFiles"></span>
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
                                                x-text="(fileData.path ? fileData.path + '/' : '') + fileData.name">
                                            </div>
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
