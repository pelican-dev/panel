<?php

return [
    'notices' => [
        'imported' => 'Berhasil mengimport Egg dan variabel terkaitnya.',
        'updated_via_import' => 'Egg ini telah diperbarui menggunakan file yang disediakan.',
        'deleted' => 'Berhasil menghapus Egg dari Panel.',
        'updated' => 'Konfigurasi Egg ini telah berhasil diperbarui.',
        'script_updated' => 'Pemasangan Script Egg telah diperbarui dan akan dijalankan setiap kali server dipasang.',
        'egg_created' => 'Telur baru berhasil dikeluarkan. Anda perlu memulai ulang semua daemon yang sedang berjalan untuk menerapkan telur baru ini.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'The variable ":variable" has been deleted and will no longer be available to servers once rebuilt.',
            'variable_updated' => 'The variable ":variable" has been updated. You will need to rebuild any servers using this variable in order to apply changes.',
            'variable_created' => 'New variable has successfully been created and assigned to this egg.',
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
