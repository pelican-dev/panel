<?php

return [
    'edit' => [
        'editing_title' => 'Editing :path',
        'save&close' => 'Save & Close',
        'save' => 'Save',
        'file_saved' => 'File saved',
        'cancel' => 'Cancel',
        'syntax_highlight' => 'Syntax Highlighting',
        'too_large' => [
            'title' => '<code> :file </code> is too large!',
            'body' => 'Max is :max_size',
        ],
        'not_found' => [
            'title' => '<code> :file </code> not found!',
        ],
        'is_directory' => [
            'title' => '<code> :file is a directory',
        ],
        'ignorefile' => [
            'alert_title' => 'You\'re editing a <code>.pelicanignore</code> file!',
            'alert_body' => 'Any files or directories listed in here will be excluded from backups. Wildcards are supported by using an asterisk (<code>*</code>).<br>You can negate a prior rule by prepending an exclamation point (<code>!</code>).',
        ],
        'connection_error' => 'Could not connect to the node!',
    ],
    'list' => [
        'open' => 'Open',
        'rename' => 'Rename',
        'rename_file' => 'File name',
        'file_renamed' => 'File renamed',
        'file_copied' => 'File copied',
        'download' => 'Download',
        'move' => 'Move',
        'move_to' => 'New location',
        'move_to_desc' => 'Enter the location of this file or folder, relative to the current directory.',
        'file_moved' => 'File moved',
        'file_permissions' => 'Permissions',
        'notice_change_perms' => 'Permissions changed to :mode',
        'archive' => 'Archive',
        'archive_name' => 'Archive name',
        'archive_created' => 'Archive created',
        'unarchive' => 'Unarchive',
        'unarchive_completed' => 'Unarchive completed',
        'deletefileconfirm' => 'Delete file?',
        'bulkmove' => [
            'label' => 'Destination Directory',
            'description' => 'Enter the new directory, relative to the current directory.',
            'notice' => ':count files were moved to :destination',
        ],
        'bulkarchive' => [
            'label' => 'Archive name',
            'archive_created' => 'Archive created',
        ],
        'bulkdelete' => [
            'notice' => ':count files delete.',
        ],
        'createfile' => [
            'new_file' => 'New File',
            'title' => 'Create',
            'file_name' => 'File Name',
            'syntax_helper' => 'Syntax Highlighting',
        ],
        'createfolder' => [
            'new_folder' => 'New Folder',
            'folder_name' => 'Folder Name',
        ],
        'upload' => [
            'label' => 'Upload',
            'uploadfiles_select' => 'Upload Files',
            'uploadfiles_fromURL' => 'Upload From URL',
        ],
        'search' => [
            'label' => 'Global Search',
            'desc' => 'Enter a search term, e.g. *.txt',
        ],
    ],
    'search' => [
        'title' => 'Global Search',
        'searchterm' => 'Search :term',
    ],
];