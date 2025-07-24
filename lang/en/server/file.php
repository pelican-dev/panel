<?php

return [
    'title' => 'Files',
    'name' => 'Name',
    'size' => 'Size',
    'modified_at' => 'Modified at',
    'actions' => [
        'open' => 'Open',
        'download' => 'Download',
        'copy' => [
            'title' => 'Copy',
            'notification' => 'File Copied',
        ],
        'upload' => [
            'title' => 'Upload',
            'from_files' => 'Upload Files',
            'from_url' => 'Upload from URL',
            'url' => 'URL',
        ],
        'rename' => [
            'title' => 'Rename',
            'file_name' => 'File Name',
            'notification' => 'File Renamed',
        ],
        'move' => [
            'title' => 'Move',
            'directory' => 'Directory',
            'directory_hint' => 'Enter the new directory, relative to the current directory.',
            'new_location' => 'New Location',
            'new_location_hint' => 'Enter the location of this file or folder, relative to the current directory.',
            'notification' => 'File Moved',
            'bulk_notification' => ':count Files were moved to :directory',
        ],
        'permissions' => [
            'title' => 'Permissions',
            'read' => 'Read',
            'write' => 'Write',
            'execute' => 'Execute',
            'owner' => 'Owner',
            'group' => 'Group',
            'public' => 'Public',
            'notification' => 'Permissions changed to :mode',
        ],
        'archive' => [
            'title' => 'Archive',
            'archive_name' => 'Archive Name',
            'notification' => 'Archive Created',
        ],
        'unarchive' => [
            'title' => 'Unarchive',
            'notification' => 'Unarchive Completed',
        ],
        'new_file' => [
            'title' => 'New file',
            'file_name' => 'New file name',
            'syntax' => 'Syntax Highlighting',
            'create' => 'Create',
        ],
        'new_folder' => [
            'title' => 'New folder',
            'folder_name' => 'New folder name',
        ],
        'global_search' => [
            'title' => 'Global Search',
            'search_term' => 'Search term',
            'search_term_placeholder' => 'Enter a search term, ex. *.txt',
            'search' => 'Search',
        ],
        'delete' => [
            'notification' => 'File Deleted',
            'bulk_notification' => ':count files were deleted',
        ],
        'edit' => [
            'title' => 'Editing: :file',
            'save_close' => 'Save & Close',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'notification' => 'File Saved',
        ],
    ],
];
