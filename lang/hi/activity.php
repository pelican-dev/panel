<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'विफल लॉग इन',
        'success' => 'लॉग इन किया गया',
        'password-reset' => 'पासवर्ड रीसेट',
        'reset-password' => 'पासवर्ड रीसेट का अनुरोध किया गया',
        'checkpoint' => 'Two-factor authentication requested',
        'recovery-token' => 'दो-कारक रिकवरी टोकन का उपयोग किया गया',
        'token' => 'दो-कारक चुनौती का समाधान किया गया',
        'ip-blocked' => 'अनसूचीबद्ध आईपी पते से :identifier के लिए अनुरोध अवरुद्ध किया गया',
        'sftp' => [
            'fail' => 'एसएफटीपी लॉगइन विफल रहा',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'ईमेल :old से बदलकर :new कर दिया गया',
            'password-changed' => 'पासवर्ड बदल दिया गया',
        ],
        'api-key' => [
            'create' => 'नई एपीआई कुंजी :identifier बनाई गई',
            'delete' => 'हटाई गई एपीआई की: पहचानकर्ता',
        ],
        'ssh-key' => [
            'create' => 'खाते में एसएसएच कुंजी :fingerprint को जोड़ा गया',
            'delete' => 'खाते से एसएसएच कुंजी :fingerprint को हटा दिया गया',
        ],
        'two-factor' => [
            'create' => 'दो-कारक प्रमाणीकरण को सक्षम किया गया',
            'delete' => 'दो-कारक प्रमाणीकरण को निष्क्रिय कर दिया गया',
        ],
    ],
    'server' => [
        'reinstall' => 'Reinstalled server',
        'console' => [
            'command' => 'Executed ":command" on the server',
        ],
        'power' => [
            'start' => 'Started the server',
            'stop' => 'Stopped the server',
            'restart' => 'Restarted the server',
            'kill' => 'Killed the server process',
        ],
        'backup' => [
            'download' => 'Downloaded the :name backup',
            'delete' => 'Deleted the :name backup',
            'restore' => 'Restored the :name backup (deleted files: :truncate)',
            'restore-complete' => 'Completed restoration of the :name backup',
            'restore-failed' => 'Failed to complete restoration of the :name backup',
            'start' => 'Started a new backup :name',
            'complete' => 'Marked the :name backup as complete',
            'fail' => 'Marked the :name backup as failed',
            'lock' => 'Locked the :name backup',
            'unlock' => 'Unlocked the :name backup',
        ],
        'database' => [
            'create' => 'Created new database :name',
            'rotate-password' => 'Password rotated for database :name',
            'delete' => 'Deleted database :name',
        ],
        'file' => [
            'compress_one' => 'Compressed :directory:file',
            'compress_other' => 'Compressed :count files in :directory',
            'read' => 'Viewed the contents of :file',
            'copy' => 'Created a copy of :file',
            'create-directory' => 'Created directory :directory:name',
            'decompress' => 'Decompressed :files in :directory',
            'delete_one' => 'Deleted :directory:files.0',
            'delete_other' => 'Deleted :count files in :directory',
            'download' => 'Downloaded :file',
            'pull' => 'Downloaded a remote file from :url to :directory',
            'rename_one' => 'Renamed :directory:files.0.from to :directory:files.0.to',
            'rename_other' => 'Renamed :count files in :directory',
            'write' => 'Wrote new content to :file',
            'upload' => 'Began a file upload',
            'uploaded' => 'Uploaded :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create_one' => 'Created :files.0',
            'create_other' => 'Created :count new files',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Deleted :files.0',
            'delete_other' => 'Deleted :count files',
            'create-directory_one' => 'Created the :files.0 directory',
            'create-directory_other' => 'Created :count directories',
            'rename_one' => 'Renamed :files.0.from to :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from ":old" to ":new"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the :name schedule',
            'update' => 'Updated the :name schedule',
            'execute' => 'Manually executed the :name schedule',
            'delete' => 'Deleted the :name schedule',
        ],
        'task' => [
            'create' => 'Created a new ":action" task for the :name schedule',
            'update' => 'Updated the ":action" task for the :name schedule',
            'delete' => 'Deleted a task for the :name schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from :old to :new',
            'description' => 'Changed the server description from :old to :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Updated the Docker Image for the server from :old to :new',
        ],
        'subuser' => [
            'create' => 'Added :email as a subuser',
            'update' => 'Updated the subuser permissions for :email',
            'delete' => 'Removed :email as a subuser',
        ],
    ],
];
