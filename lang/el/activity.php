<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Η σύνδεση απέτυχε',
        'success' => 'Έγινε σύνδεση',
        'password-reset' => 'Επαναφορά κωδικού πρόσβασης',
        'reset-password' => 'Έγινε αίτηση επαναφοράς κωδικού πρόσβασης',
        'checkpoint' => 'Ζητήθηκε έλεγχος ταυτότητας δύο παραγόντων',
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
            'restore-failed' => 'Απέτυχε η αποκατάσταση του αντιγράφου ασφαλείας :name',
            'start' => 'Ξεκίνησε η δημιουργία ενός νέου αντιγράφου ασφαλείας :name',
            'complete' => 'Επισημάνθηκε το αντίγραφο ασφαλείας :name ως ολοκληρωμένο',
            'fail' => 'Επισημάνθηκε το αντίγραφο ασφαλείας :name ως αποτυχημένο',
            'lock' => 'Κλειδώθηκε το αντίγραφο ασφαλείας :name',
            'unlock' => 'Ξεκλειδώθηκε το αντίγραφο ασφαλείας :name',
        ],
        'database' => [
            'create' => 'Δημιουργήθηκε νέα βάση δεδομένων :name',
            'rotate-password' => 'Πραγματοποιήθηκε αλλαγή του κωδικού πρόσβασης για τη βάση δεδομένων :name',
            'delete' => 'Διαγράφηκε η βάση δεδομένων :name',
        ],
        'file' => [
            'compress_one' => 'Συμπιέστηκε :directory:file',
            'compress_other' => 'Συμπιέστηκαν :count αρχεία σε :directory',
            'read' => 'Προβλήθηκαν τα περιεχόμενα του :file',
            'copy' => 'Δημιουργήθηκε ένα αντίγραφο του :file',
            'create-directory' => 'Δημιουργήθηκε ο φάκελος :directory:name',
            'decompress' => 'Αποσυμπιέστηκαν :files αρχεία σε :directory',
            'delete_one' => 'Διαγράφτηκε :Directory:files.0',
            'delete_other' => 'Διαγράφτηκαν :count αρχεία στο :directory',
            'download' => 'Έγινε λήψη του αρχείου :file',
            'pull' => 'Έγινε λήψη ενός απομακρυσμένου αρχείου από :url σε :directory',
            'rename_one' => 'Μετονομάστηκε :directory:files.0.from σε :directory:files.0.to',
            'rename_other' => 'Μετονομάστηκαν :count αρχεία στο :directory',
            'write' => 'Προστέθηκε νέο περιεχόμενο στο :file',
            'upload' => 'Η μεταφόρτωση αρχείων ξεκίνησε',
            'uploaded' => 'Ανέβηκε :directory:file',
        ],
        'sftp' => [
            'denied' => 'Αποκλείστηκε η πρόσβαση SFTP λόγω αδειών',
            'create_one' => 'Δημιουργήθηκαν :files.0',
            'create_other' => 'Δημιουργήθηκαν :count νέα αρχεία',
            'write_one' => 'Επεξεργάστηκε το περιεχόμενο του :files.0',
            'write_other' => 'Επεξεργάστηκαν τα περιεχόμενα :count αρχείων',
            'delete_one' => 'Διαγράφτηκε :files.0',
            'delete_other' => 'Διαγράφτηκαν :count αρχεία',
            'create-directory_one' => 'Δημιουργήθηκε ο φάκελος :files.0',
            'create-directory_other' => 'Δημιουργήθηκαν :count φάκελοι',
            'rename_one' => 'Πραγματοποιήθηκε μετονομασία του/των :files.0.from σε :files.0.to',
            'rename_other' => 'Μετανομάστηκαν ή μετακινήθηκαν :count αρχεία',
        ],
        'allocation' => [
            'create' => 'Προστέθηκε :allocation στον διακομιστή',
            'notes' => 'Ενημερώθηκαν οι σημειώσεις για :allocation από ":old" σε ":new"',
            'primary' => 'Ορίστηκε :allocation ως κύριο allocation του διακομιστή',
            'delete' => 'Διαγράφτηκε το allocation :allocation',
        ],
        'schedule' => [
            'create' => 'Δημιουργήθηκε το χρονοδιάγραμμα :name',
            'update' => 'Ενημερώθηκε το χρονοδιάγραμμα :name',
            'execute' => 'Χειροκίνητη εκτέλεση του χρονοδιαγράμματος :name',
            'delete' => 'Διαγράφηκε το χρονοδιάγραμμα :name',
        ],
        'task' => [
            'create' => 'Δημιουργήθηκε μια νέα εργασία ":action" για το χρονοδιάγραμμα :name',
            'update' => 'Ενημερώθηκε η εργασία ":action" για το χρονοδιάγραμμα :name',
            'delete' => 'Διαγράφηκε μια εργασία για το χρονοδιάγραμμα :name',
        ],
        'settings' => [
            'rename' => 'Μετονομάστηκε ο διακομιστής από :old σε :new',
            'description' => 'Άλλαξε η περιγραφή του διακομιστή από :old σε :new',
        ],
        'startup' => [
            'edit' => 'Άλλαξε η μεταβλητή :variable από ":old" σε ":new"',
            'image' => 'Ενημέρωση εικόνας Docker για το διακομιστή από :old σε :new',
        ],
        'subuser' => [
            'create' => 'Προστέθηκε :email ως υποχρήστης',
            'update' => 'Ενημερώθηκαν τα δικαιώματα του υποχρήστη για :email',
            'delete' => 'Αφαιρέθηκε :email ως υποχρήστης',
        ],
    ],
];
