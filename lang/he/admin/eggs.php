<?php

return [
    'notices' => [
        'imported' => 'ביצה זו והמשתנים הקשורים לה יובאו בהצלחה.',
        'updated_via_import' => 'ביצה זו עודכנה באמצעות הקובץ שסופק.',
        'deleted' => 'נמחקה בהצלחה הביצה המבוקשת מהחלונית.',
        'updated' => 'תצורת הביצה עודכנה בהצלחה.',
        'script_updated' => 'סקריפט התקנת הביצה עודכן ויפעל בכל פעם שיותקנו שרתים.',
        'egg_created' => 'ביצה חדשה הוטלה בהצלחה. תצטרך להפעיל מחדש את כל הדמונים הפועלים כדי להחיל את התיקון החדש הזה.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'המשתנה ":variable" נמחק ולא יהיה זמין יותר לשרתים לאחר הבנייה מחדש.',
            'variable_updated' => 'המשתנה ":variable" עודכן. תצטרך לבנות מחדש את כל השרתים המשתמשים במשתנה זה כדי להחיל שינויים.',
            'variable_created' => 'משתנה חדש נוצר בהצלחה והוקצה לביצה זו.',
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
