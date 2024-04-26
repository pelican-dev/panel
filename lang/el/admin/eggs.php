<?php

return [
    'notices' => [
        'imported' => 'Επιτυχής εισαγωγή του αυγού και των σχετικών μεταβλητών του.',
        'updated_via_import' => 'Αυτό το αυγό ενημερώθηκε χρησιμοποιώντας το αρχείο που παρείχατε.',
        'deleted' => 'Διαγράφηκε με επιτυχία το egg που ζητήθηκε από το panel.',
        'updated' => 'Η ρύθμιση παραμέτρων αυτού του egg έχουν ενημερωθεί με επιτυχία.',
        'script_updated' => 'Το σενάριο εγκατάστασης για τα eggs έχει ενημερωθεί και θα εκτελείται όποτε εγκαθίστανται οι διακομιστές.',
        'egg_created' => 'Ένα νέο egg ορίστηκε με επιτυχία. Θα πρέπει να επανεκκινήσετε τυχόν εκτελούμενα daemons για να εφαρμόσετε αυτό το νέο egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Η μεταβλητή ":variable" έχει διαγραφεί και δεν θα είναι πλέον διαθέσιμη στους διακομιστές μετά το rebuild.',
            'variable_updated' => 'Η μεταβλητή ":variable" έχει ενημερωθεί. Θα χρειαστεί να κάνετε rebuild όλους τους διακομιστές που χρησιμοποιούν αυτήν τη μεταβλητή για να εφαρμοστούν οι αλλαγές.',
            'variable_created' => 'Η νέα μεταβλητή δημιουργήθηκε και αντιστοιχίστηκε με επιτυχία σε αυτό το egg.',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
