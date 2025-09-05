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
            'search_for_term' => 'Search :term',
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
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> is too large!',
            'body' => 'Max is :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> not found!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> is a directory',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> already exists!',
        ],
        'files_node_error' => [
            'title' => 'Could not load files!',
        ],
        'pelicanignore' => [
            'title' => 'You are editing a <code>.pelicanignore</code> file!',
            'body' => 'Any files or directories listed in here will be excluded from backups. Wildcards are supported by using an asterisk (<code>*</code>).<br>You can negate a prior rule by prepending an exclamation point (<code>!</code>).',
        ],
    ],
];
