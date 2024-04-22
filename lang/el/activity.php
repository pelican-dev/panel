<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Αποτυχημένη σύνδεση',
        'success' => 'Συνδεδεμένος',
        'password-reset' => 'Επαναφορά κωδικού πρόσβασης',
        'reset-password' => 'Αίτηση επαναφοράς κωδικού πρόσβασης',
        'checkpoint' => 'Απαιτήθηκε η ταυτοποίηση δύο παραγόντων',
        'recovery-token' => 'Χρησιμοποιήθηκε κλειδί αποκατάστασης ταυτοποίησης δύο παραγόντων',
        'token' => 'Επιλύθηκε η ταυτοποίηση δύο παραγόντων',
        'ip-blocked' => 'Αποκλείστηκε αίτημα από μη καταχωρημένη διεύθυνση IP για :identifier',
        'sftp' => [
            'fail' => 'Αποτυχημένη σύνδεση SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Πραγματοποιήθηκε αλλαγή του email από :old σε :new',
            'password-changed' => 'Πραγματοποιήθηκε αλλαγή του κωδικού πρόσβασης',
        ],
        'api-key' => [
            'create' => 'Δημιουργήθηκε νέο κλειδί API :identifier',
            'delete' => 'Διαγράφηκε το κλειδί API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Προστέθηκε νέο κλειδί SSH :fingerprint στον λογαριασμό',
            'delete' => 'Αφαιρέθηκε το κλειδί SSH :fingerprint απο τον λογαριασμό',
        ],
        'two-factor' => [
            'create' => 'Ενεργοποιήθηκε η ταυτοποίηση δύο παραγόντων',
            'delete' => 'Απενεργοποιήθηκε η ταυτοποίηση δύο παραγόντων',
        ],
    ],
    'server' => [
        'reinstall' => 'Εκτελέστηκε επανεγκατάσταση του διακομιστή',
        'console' => [
            'command' => 'Εκτελέστηκε ":command" στον διακομιστή',
        ],
        'power' => [
            'start' => 'Εκτελέστηκε εκκίνηση του διακομιστή',
            'stop' => 'Εκτελέστηκε τερματισμός του διακομιστή',
            'restart' => 'Εκτελέστηκε επανεκκίνηση του διακομιστή',
            'kill' => 'Διακόπηκε η διεργασία του διακομιστή',
        ],
        'backup' => [
            'download' => 'Έγινε λήψη του αντιγράφου ασφαλείας :name',
            'delete' => 'Διαγράφηκε το αντίγραφο ασφαλείας :name',
            'restore' => 'Έγινε επαναφορά του αντιγράφου ασφαλείας :name (διαγραμμένα αρχεία: :truncate)',
            'restore-complete' => 'Ολοκληρώθηκε η αποκατάσταση του αντιγράφου ασφαλείας :name',
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
