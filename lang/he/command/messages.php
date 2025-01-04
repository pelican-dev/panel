<?php

return [
    'user' => [
        'search_users' => 'הזן שם משתמש, מזהה משתמש או כתובת דואר אלקטרוני',
        'select_search_user' => 'מזהה המשתמש שיש למחוק (הזן \'0\' כדי לחפש מחדש)',
        'deleted' => 'המשתמש נמחק בהצלחה מהחלונית.',
        'confirm_delete' => 'האם אתה בטוח שברצונך למחוק את המשתמש הזה מהחלונית?',
        'no_users_found' => 'לא נמצאו משתמשים עבור מונח החיפוש שסופק.',
        'multiple_found' => 'נמצאו חשבונות מרובים עבור המשתמש שסופק, לא ניתן למחוק משתמש בגלל הדגל --no-interaction.',
        'ask_admin' => 'האם משתמש זה הוא מנהל מערכת?',
        'ask_email' => 'כתובת דוא"ל',
        'ask_username' => 'שם משתמש',
        'ask_password' => 'סיסמה',
        'ask_password_tip' => 'אם ברצונך ליצור חשבון עם סיסמה אקראית שנשלחת באימייל למשתמש, הפעל מחדש את הפקודה הזו (CTRL+C) והעביר את הדגל `--no-password`.',
        'ask_password_help' => 'סיסמאות חייבות להיות באורך של לפחות 8 תווים ולהכיל לפחות אות גדולה ומספר אחד.',
        '2fa_help_text' => [
            'פקודה זו תשבית אימות דו-שלבי עבור חשבון משתמש אם היא מופעלת. זה אמור לשמש כפקודה לשחזור חשבון רק אם המשתמש ננעל מחוץ לחשבון שלו.',
            'אם זה לא מה שרצית לעשות, הקש CTRL+C כדי לצאת מהתהליך הזה.',
        ],
        '2fa_disabled' => 'אימות דו-שלבי הושבת עבור :email.',
    ],
    'schedule' => [
        'output_line' => 'שולח עבודה למשימה ראשונה ב-`:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'מחיקת קובץ גיבוי שירות: :file.',
    ],
    'server' => [
        'rebuild_failed' => 'בקשת בנייה מחדש עבור ":name" (#:id) בצומת ":node" נכשלה עם שגיאה: :message',
        'reinstall' => [
            'failed' => 'בקשת התקנה מחדש עבור ":name" (#:id) בצומת ":node" נכשלה עם שגיאה: :message',
            'confirm' => 'אתה עומד להתקין מחדש מול קבוצת שרתים. האם אתה מקווה להמשיך?',
        ],
        'power' => [
            'confirm' => 'אתה עומד לבצע :פעולה נגד :count שרתים האם ברצונך להמשיך?',
            'action_failed' => 'בקשת פעולת הפעלה עבור ":name" (#:id) בצומת ":node" נכשלה עם שגיאה: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'מארח SMTP (למשל smtp.gmail.com)',
            'ask_smtp_port' => 'יציאת SMTP',
            'ask_smtp_username' => 'שם משתמש SMTP',
            'ask_smtp_password' => 'סיסמאת SMTP',
            'ask_mailgun_domain' => 'דומיין Mailgun',
            'ask_mailgun_endpoint' => 'נקודת קצה של Mailgun',
            'ask_mailgun_secret' => 'הסוד של Mailgun',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API Key',
            'ask_driver' => 'Which driver should be used for sending emails?',
            'ask_mail_from' => 'Email address emails should originate from',
            'ask_mail_name' => 'Name that emails should appear from',
            'ask_encryption' => 'Encryption method to use',
        ],
    ],
];
